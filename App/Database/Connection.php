<?php

namespace App\Database;

use \PDO;
use \PDOException;

class Connection
{
    private string $host = 'localhost';
    private string $dbname = 'p5blog';
    private string $username = 'p5blog';
    private string $password = 'p5blog';
    private ?\PDO $conn;

    private function connect() : PDO
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }

    public function getSingleAsClass($statement, $class_name) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
            $req->execute();
            return $req->fetch();
        } catch (PDOException $e) {
            echo "getAsClass Error: " . $e->getMessage();
        }
    }

}