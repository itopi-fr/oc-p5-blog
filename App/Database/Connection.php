<?php

namespace App\Database;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Post;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserOwner;
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
    protected MainController $mc;



    protected function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->mc = new MainController();
    }

    /**
     * Creates a PDO connection to the database.
     * @return PDO
     */
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

    /**
     * Returns a single result as specific class object.
     * @param string $statement
     * @param array $data
     * @param string $class_name
     * @return null|Comment|File|Post|Token|User|UserOwner
     * @throws PDOException
     */
    public function getSingleAsClass(
        string $statement,
        array $data,
        string $class_name
    ) : null | Comment | File | Post | Token | User | UserOwner {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
            $req->execute($data);
            $result = $req->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Returns a single element as an object.
     * @param string $statement
     * @param array $data
     * @return mixed|false
     * @throws PDOException
     */
    public function getSingleAsObject($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            $result = $req->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Returns a multiple element as an array of objects.
     * @param string $statement
     * @param array $data
     * @return array|null
     * @throws PDOException
     */
    public function getMultipleAsObjectsArray($statement, $data) {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            $result = $req->fetchAll();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Inserts a single element into the database. Returns the last inserted id.
     * @param string $statement
     * @param array $data
     * @return string|null
     * @throws PDOException
     */
    protected function insert($statement, $data): string | null
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
            $result = $this->connect()->lastInsertId();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Deletes a single element in the database.
     * @param string $statement
     * @param array $data
     * @return bool
     * @throws PDOException
     */
    protected function delete($statement, $data)
    {
        try {
            $req = $this->connect()->prepare($statement);
            return $req->execute($data);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }


}