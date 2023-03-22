<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\Token;
use DateTime;
use PDOException;

class TokenModel extends Connection
{
    public Token $token;
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Returns all tokens for a given user as an array of objects.
     * @param int $userId
     * @return array|null
     */
    public function getUserTokens(int $userId): array|null
    {
        $req = "SELECT * FROM token WHERE user_id =?";
        $result = $this->getMultipleAsObjectsArray($req, [$userId]);
        return $result ? $result : null;
    }

    /**
     * Returns a token object based on its id.
     * @param int $id
     * @return object|null
     */
    public function getTokenById(int $tokenId): object|null
    {
        $req = 'SELECT * FROM token WHERE id = ?';
        $result = $this->getSingleAsObject($req, [$tokenId]);
        return $result ? $result : null;
    }

    /**
     * Returns a token object based on its content.
     * @param string $tokenContent
     * @return object|null
     */
    public function getTokenByContent(string $tokenContent): object | null
    {
        $req = 'SELECT * FROM token WHERE content =?';
        $result = $this->getSingleAsObject($req, [$tokenContent]);
        return $result ? $result : null;
    }

    /**
     * Inserts a token in the database.
     * @param Token $token
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

        return (!is_null($result)) ? $result : null;
    }

    /**
     * Deletes a token from the database based on its id.
     * @param int $tokenId
     * @return bool
     */
    public function deleteTokenById(int $tokenId)
    {
        $req = "DELETE FROM token WHERE id =?";
        $result = $this->delete($req, [$tokenId]);
        return $result;
    }


}