<?php

namespace App\Database;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Post;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserOwner;
use PDO;
use PDOException;
use App\Controller\MainController;

class Connection
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private ?PDO $conn;



    protected function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    /**
     * Creates a PDO connection to the database.
     * @return PDO
     */
    private function connect(): PDO
    {
        try {
            if (!isset($this->conn)) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // mysql bug : int converted to string
            }
        } catch (PDOException $e) {
            throw new PDOException($e);
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
    ): null | Comment | File | Post | Token | User | UserOwner {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
            $req->execute($data);
            $result = $req->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Returns a single element as an object.
     * @param string $statement
     * @param array $data
     * @return mixed
     * @throws PDOException
     */
    public function getSingleAsObject(string $statement, array $data): mixed
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            $result = $req->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Returns a multiple element as an array of objects.
     * @param string $statement
     * @param array $data
     * @return array|null
     * @throws PDOException
     */
    public function getMultipleAsObjectsArray(string $statement, array $data): array|null
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute($data);
            $result = $req->fetchAll();


            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Inserts a single element into the database. Returns the id of the last inserted element or a null value.
     * @param string $statement
     * @param array $data
     * @return int|null
     * @throws PDOException
     */
    protected function insert(string $statement, array $data): int | null
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);

            $result = $this->connect()->lastInsertId();
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Deletes a single element in the database.
     * @param string $statement
     * @param array $data
     * @return bool|null
     * @throws PDOException
     */
    protected function delete(string $statement, array $data): bool | null
    {
        try {
            $req = $this->connect()->prepare($statement);
            $result = $req->execute($data);
            return $result ? $result : null;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Checks if a single element exists in the database.
     * @param string $statement
     * @param array $data
     * @return bool
     * @throws PDOException
     */
    protected function exists(string $statement, array $data): bool
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
            $res = $req->fetch();
            return $res[0] === 1;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     * Updates a single element in the database.
     * @param string $statement
     * @param array $data
     * @return int
     * @throws PDOException
     */
    protected function update(string $statement, array $data): int
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
            return $req->rowCount();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    protected function getMaxId(string $statement, array $data): int
    {
        try {
            $req = $this->connect()->prepare($statement);
            $req->execute($data);
            $res = $req->fetch();
            return $res[0];
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}
