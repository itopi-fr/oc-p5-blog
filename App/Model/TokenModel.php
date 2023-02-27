<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Token;
use DateTime;

class TokenModel extends Connection
{
    public Token $token;
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Returns all tokens for a given user.
     * @param int $userId
     * @return array
     */
    public function getUserTokens(int $userId)
    {
        $req = "SELECT * FROM token WHERE user_id =?";
        return $this->getMultipleAsObjectsArray($req, [$userId]);
    }

    /**
     * Returns a token object based on its id.
     * @param int $id
     * @return object
     */
    public function getTokenById(int $tokenId) {
        $req = 'SELECT * FROM token WHERE id = ?';
        return $this->getSingleAsObject($req, [$tokenId]);
    }

    /**
     * Returns a token object based on its content.
     * @param string $content
     * @return Token
     */
    public function getTokenByContent(string $content) {
        $req = 'SELECT * FROM token WHERE content =?';
        return $this->getSingleAsObject($req, [$content]);
    }

    /**
     * Inserts a token in the database.
     * @param Token $token
     * @return void
     */
    public function insertPassChangeToken(Token $token) {
        $req = "INSERT INTO token (user_id, content, expiration_date, type)
                VALUES (:user_id, :content, :expiration_date, :type)";

        $params = array(
            'user_id' => $token->getUserId(),
            'content' => $token->getContent(),
            'expiration_date' => $token->getExpirationDate()->format('Y-m-d H:i:s'),
            'type' => $token->getType()
        );

        $this->insert($req, $params);
    }

    /**
     * Deletes a token from the database based on its id.
     * @param int $tokenId
     * @return void
     */
    public function deleteTokenById(int $tokenId)
    {
        $req = "DELETE FROM token WHERE id =?";
        $this->delete($req, [$tokenId]);
    }


}