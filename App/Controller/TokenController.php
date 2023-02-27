<?php

namespace App\Controller;

use App\Entity\Token;
use App\Model\TokenModel;
use App\Model\UserModel;
use DateTime;

class TokenController extends MainController
{
    private Token $token;
    private TokenModel $tokenModel;
    private UserModel $userModel;


    public function __construct()
    {
        parent::__construct();
        $this->token =      new Token();
        $this->tokenModel = new TokenModel();
        $this->userModel =  new UserModel();
    }

    /**
     * Builds a token object from a database object.
     * @param object $tokenObj
     * @return Token
     */
    private function buildToken(object $tokenObj) {
        $this->token = new Token();
        $this->token->setId($tokenObj->id);
        $this->token->setUserId($tokenObj->user_id);
        $this->token->setContent($tokenObj->content);
        $this->token->setExpirationDate(new DateTime($tokenObj->expiration_date));
        $this->token->setType($tokenObj->type);
        return $this->token;
    }

    /**
     * Returns a token object based on its id or its content.
     * @param int|string $data
     * @return Token
     */
    public function getToken(int | string $data) {
        if (is_int($data)) {
            return $this->buildToken($this->tokenModel->getTokenById($data));
        } else if (is_string($data)) {
            return $this->buildToken($this->tokenModel->getTokenByContent($data));
        }
    }

    /**
     * Returns all tokens from a user.
     * @param int $userId
     * @return array
     */
    public function getUserTokens(int $userId) : array
    {
        return $this->tokenModel->getUserTokens($userId);
    }


    /**
     * Returns the last valid token from a user.
     * @param int $userId
     * @return null|Token
     */
    public function getLastValidTokenByUserId(int $userId) : null|Token
    {
        $tokens = $this->getUserTokens($userId);

        if (empty($tokens)) {
            return null;
        }

        $user = $this->userModel->getUserById($userId);

        foreach ($tokens as $key => $token) {
            if ($this->verifyPassChangeToken($token->content, $user->getEmail()) !== "token-ok") {
                unset($tokens[$key]);
            }
        }
        return $this->buildToken(end($tokens));
    }


    /**
     * Creates a token for password change. This token is valid for 15 minutes.
     * If a valid token already exists, it is kept and nothing is done.
     * If expired tokens exist, they are deleted.
     * @param int $userId
     * @return void
     */
    public function createPassChangeToken(int $userId) {
        // Delete expired tokens
        $this->deleteExpiredTokens($userId);

        // If a valid token already exists, do nothing
        if (!is_null($this->getLastValidTokenByUserId($userId))) {
            return;
        }

        // Create a new token
        $this->token->setUserId($userId);
        $this->token->setContent($this->generateKey());
        $this->token->setExpirationDate(new DateTime('now + 15 minutes'));
        $this->token->setType('password_change');
        $this->tokenModel->insertPassChangeToken($this->token);
    }


    /**
     * Deletes expired tokens from the database based on the user_id
     * @param int $userId
     * @return void
     */
    public function deleteExpiredTokens(int $userId) {
        $tokens = $this->getUserTokens($userId);
        $user = $this->userModel->getUserById($userId);

        foreach ($tokens as $token) {
            if ($this->verifyPassChangeToken($token->content, $user->getEmail()) !== "token-ok") {
                $this->tokenModel->deleteTokenById($token->id);
            }
        }
    }

    /**
     * Deletes a token from the database based on its id.
     * @param int $tokenId
     * @return void
     */
    public function deleteTokenById(int $tokenId) : void {
        $this->tokenModel->deleteTokenById($tokenId);
    }


    /**
     * Verifies if a token is valid or not based on its expiration date and verification that it matches the hash.
     * If the token is expired, it is deleted from the database.
     * @param string $token
     * @param string $email
     * @return string
     */
    public function verifyPassChangeToken(string $token, string $email)
    {
        $this->token = $this->buildToken($this->tokenModel->getTokenByContent($token));

        $user = $this->userModel->getUserByEmail($email);
        if ($user === null) {
            return "token-user-not-found";
        }
        if ($this->token->getUserId() === $user->getId()) {
            if ($this->token->getExpirationDate() > new DateTime()) {
                return "token-ok";
            } else {
                $this->deleteTokenById($this->token->getId());
                return "token-expired";
            }
        }
        return "token-mail-invalid";
    }


}