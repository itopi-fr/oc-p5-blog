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

    public function getTokenById(int $id) {
        $req = "SELECT * FROM token WHERE id = " . $id;
        $tokenObj = $this->getSingleAsObject($req);
        $this->token = new Token();
        $this->token->setId($tokenObj->id);
        $this->token->setUserId($tokenObj->user_id);
        $this->token->setContent($tokenObj->content);
        $this->token->setExpirationDate(new DateTime($tokenObj->expiration_date));
        return $this->token;
    }

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
}