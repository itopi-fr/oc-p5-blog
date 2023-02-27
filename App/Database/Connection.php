<?php

namespace App\Database;

use \PDO;
use \PDOException;
use App\Controller\MainController;

class Connection
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private ?\PDO $conn;
    private MainController $mc;



    protected function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->mc = new MainController();
    }

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
            $result = $req->fetch();
            if (is_bool($result)) {
                return null;     // Return null when no result is found
            }
            return $result;
        } catch (PDOException $e) {
            echo "getSingleAsClass Error: " . $e->getMessage();
        }
    }

    public function getSingleAsObject($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            return $req->fetch();
        } catch (PDOException $e) {
            echo "getSingleAsObject Error: " . $e->getMessage();
        }
    }

    public function getMultipleAsObjectsArray($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            return $req->fetchAll();
        } catch (PDOException $e) {
            echo "getMultipleAsObjectsArray Error: " . $e->getMessage();
        }
    }

    protected function insert($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
            return $this->connect()->lastInsertId();
        } catch (PDOException $e) {
            echo "insert Error: " . $e->getMessage();
        }
    }

    protected function delete($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
        } catch (PDOException $e) {
            echo "delete Error: " . $e->getMessage();
        }
    }


}