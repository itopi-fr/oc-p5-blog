<?php

namespace App\Model;

use App\Database\Connection;

class UserModel
{
    private Connection $db;
    public function __construct()
    {
        $this->db = new Connection();
        $this->db->connect();
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }
}