<?php

namespace App\Controller;

use App\Entity\Res;
use App\Entity\Token;
use App\Model\TokenModel;
use App\Model\UserModel;
use DateTime;
use Exception;

class TokenController extends MainController
{
    private Res $res;

    private Token $token;
    private TokenModel $tokenModel;
    private UserModel $userModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res =        new Res();
        $this->token =      new Token();
        $this->tokenModel = new TokenModel();
        $this->userModel =  new UserModel();
    }

    /**
     * Builds a token object from a database object.
     * @param object $tokenObj
     * @return Token
     * @throws Exception
     */
    private function buildToken(object $tokenObj): Token
    {
        $this->dump($tokenObj);
        $this->token = new Token();
        $this->token->setTokenId($tokenObj->token_id);
        $this->token->setUserId($tokenObj->user_id);
        $this->token->setContent($tokenObj->content);
        $this->token->setExpirationDate(new DateTime($tokenObj->expiration_date));
        $this->token->setType($tokenObj->type);
        return $this->token;
    }

    /**
     * Returns a token object based on its id or its content.
     * @param int|string $data
     * @return Res
     * @throws Exception
     */
    public function getToken(int | string $data): Res
    {
        if (is_int($data) === true) {
            $tokenObj = $this->tokenModel->getTokenById($data);
            if ($tokenObj === null) {
                $this->res->ko('token', 'token-id-not-found');
            } else {
                $this->res->ok('token', 'token-found', $this->buildToken($tokenObj));
            }
        } elseif (is_string($data) === true) {
            $tokenObj = $this->tokenModel->getTokenByContent($data);
            if ($tokenObj === null) {
                $this->res->ko('token', 'token-content-not-found');
            } else {
                $this->res->ok('token', 'token-found', $this->buildToken($tokenObj));
            }
        } else {
            $this->res->ko('token', 'token-invalid-data-type');
        }
        return $this->res;
    }

    /**
     * Returns all tokens from a given type from a user.
     * @param int $userId
     * @param string $tokenType
     * @return array|null
     */
    public function getUserTokens(int $userId, string $tokenType): array|null
    {
        $result = $this->tokenModel->getUserTokens($userId, $tokenType);
        return ($result !== null) ? $result : null;
    }


    /**
     * Returns the last valid token from a user.
     * @param int $userId
     * @param string $tokenType
     * @return null|Token
     * @throws Exception
     */
    public function getLastValidTokenByUserId(int $userId, string $tokenType): null|Token
    {
        $tokens = $this->getUserTokens($userId, $tokenType);

        if ($tokens === null) {
            return null;
        }

        $user = $this->userModel->getUserById($userId);

        foreach ($tokens as $key => $token) {
            if (
                $this->verifyToken(
                    $token->content,
                    $user->getEmail()
                )->getResult()['token-verify'] !== "verify-token-ok"
            ) {
                unset($tokens[$key]);
            }
        }
        return $this->buildToken(end($tokens));
    }


    /**
     * Creates and inserts a token in the database. This token is valid for 15 minutes.
     * If a valid token already exists, it is kept and nothing is done.
     * If expired tokens exist, they are deleted from the database.
     * @param int $userId
     * @param string $tokenType
     * @return Res
     */
    public function createUserToken(int $userId, string $tokenType): Res
    {
        // Delete expired tokens.
        $this->deleteExpiredTokens($userId, $tokenType);

        // If a valid token already exists, do nothing.
        if ($this->getLastValidTokenByUserId($userId, $tokenType) !== null) {
            $this->res->ok('token', $this->res->showMsg('valid-token-exists'));
            return $this->res;
        }

        // Create a new token.
        $this->token->setUserId($userId);
        $this->token->setContent($this->generateKey(32));
        $this->token->setExpirationDate(new DateTime('now + 15 minutes'));
        $this->token->setType($tokenType);

        if ($this->tokenModel->insertUserToken($this->token)) {
            $this->res->ok('token', $this->res->showMsg('token-created'), $this->token->getContent());
        } else {
            $this->res->ko('token', $this->res->showMsg('token-not-created'));
        }

        return $this->res;
    }


    /**
     * Deletes expired tokens from the database based on the user_id
     * @param int $userId
     * @param string $tokenType
     * @return void
     */
    public function deleteExpiredTokens(int $userId, string $tokenType): void
    {
        $tokens = $this->getUserTokens($userId, $tokenType);
        $user = $this->userModel->getUserById($userId);

        if ($tokens !== null) {
            foreach ($tokens as $token) {
                if (
                    $this->verifyToken(
                        $token->content,
                        $user->getEmail()
                    )->getResult()['verify-token'] !== "verify-token-ok"
                ) {
                    $this->tokenModel->deleteTokenById($token->id);
                }
            }
        }
    }

    /**
     * Deletes a token from the database based on its id.
     * @param int $tokenId
     * @return void
     */
    public function deleteTokenById(int $tokenId): void
    {
        $this->tokenModel->deleteTokenById($tokenId);
    }


    /**
     * Verifies if a token is valid or not based on its expiration date and verification that it matches the hash.
     * If the token is expired, it is deleted from the database.
     * @param string $tokenContent
     * @param string $email
     * @return Res
     */
    public function verifyToken(string $tokenContent, string $email): Res
    {
        // Get Token.
        $getToken = $this->tokenModel->getTokenByContent($tokenContent);

        if ($getToken === null) {
            $this->res->ko('verify-token', 'verify-token-content-not-found');
            return $this->res;
        }

        // Build Token.
        $this->token = $this->buildToken($getToken);

        // Get User.
        $user = $this->userModel->getUserByEmail($email);
        if ($user === null) {
            $this->res->ko('verify-token', 'verify-token-user-by-mail-not-found');
            return $this->res;
        }

        // Check that User ids match.
        if ($this->token->getUserId() !== $user->getUserId()) {
            $this->res->ko('verify-token', 'verify-token-user-id-not-match');
            return $this->res;
        }

        // Check that Token is not expired.
        if ($this->token->getExpirationDate() < new DateTime()) {
            $this->res->ko('verify-token', 'verify-token-expired');
            $this->deleteTokenById($this->token->getTokenId());
            return $this->res;
        }

        // if everything is ok.
        $this->res->ok('verify-token', 'verify-token-ok', $this->token);
        return $this->res;
    }
}
