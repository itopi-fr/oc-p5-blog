<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Token;

/**
 * Class TokenModel - Manages the tokens in the database
 */
class TokenModel extends Connection
{
    /**
     * @var Token
     */
    public Token $token;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Returns all tokens for a given user as an array of objects.
     *
     * @param int $userId - The id of the user to get the tokens from
     * @param string $tokenType - The type of the token to get
     * @return array|null
     */
    public function getUserTokens(int $userId, string $tokenType): array|null
    {
        $req = "SELECT * FROM token WHERE user_id = ? AND type = ?";
        $result = $this->getMultipleAsObjectsArray($req, [$userId, $tokenType]);
        return $result ? $result : null;
    }


    /**
     * Returns a token object based on its id.
     *
     * @param int $tokenId - The id of the token to get
     * @return object|null
     */
    public function getTokenById(int $tokenId): object|null
    {
        $req = 'SELECT * FROM token WHERE token_id = ?';
        $result = $this->getSingleAsObject($req, [$tokenId]);
        return $result ? $result : null;
    }


    /**
     * Returns a token object based on its content.
     *
     * @param string $tokenContent - The content of the token to get
     * @return object|null
     */
    public function getTokenByContent(string $tokenContent): object|null
    {
        $req = 'SELECT * FROM token WHERE content =?';
        $result = $this->getSingleAsObject($req, [$tokenContent]);
        return $result ? $result : null;
    }


    /**
     * Inserts a token in the database.
     *
     * @param Token $token - The token to insert
     * @return bool|null
     */
    public function insertUserToken(Token $token): bool|null
    {
        $req = "INSERT INTO token (user_id, content, expiration_date, type)
                VALUES (:user_id, :content, :expiration_date, :type)";

        $params = array(
            'user_id' => $token->getUserId(),
            'content' => $token->getContent(),
            'expiration_date' => $token->getExpirationDate()->format('Y-m-d H:i:s'),
            'type' => $token->getType()
        );
        $result = $this->insert($req, $params);

        return ($result !== null) ? $result : null;
    }


    /**
     * Deletes a token from the database based on its id.
     *
     * @param int $tokenId - The id of the token to delete
     * @return bool|null
     */
    public function deleteTokenById(int $tokenId): bool|null
    {
        $req = "DELETE FROM token WHERE token_id =?";
        return $this->delete($req, [$tokenId]);
    }


}
