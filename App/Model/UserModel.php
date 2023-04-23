<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\User;
use App\Entity\File;
use App\Entity\Token;
use Exception;

class UserModel extends Connection
{
    public User $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks if a value is unique in the user table
     * @param string $value
     * @param string $field
     * @param int $id
     * @return bool
     */
    public function isUnique(string $value, string $field, int $id): bool
    {
        $sql = "SELECT * FROM user WHERE $field = ? AND id != ?";
        // TODO: replace by exists().
        $result = $this->getSingleAsClass($sql, [$value, $id], 'App\Entity\User');
        return $result === null;
    }

    /**
     * Returns a user object based on its id.
     * @param int $userId
     * @return User|null
     */
    public function getUserById(int $userId): User|null
    {

        $sqlUser = "SELECT * FROM user WHERE id =?";
        $this->user = $this->getSingleAsClass($sqlUser, [$userId], 'App\Entity\User');

        $fileModel = new FileModel();

        if (is_null($this->user->getAvatarId()) === false) {
            $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
        } else {
            $this->user->setAvatarFile(new File());
        }
        return $this->user;
    }

    /**
     * Returns a user object based on its email.
     * @param string $userEmail
     * @return null|User
     */
    public function getUserByEmail(string $userEmail): null|User
    {
        try {
            $sql = 'SELECT * FROM user WHERE email =?';
            $result = $this->getSingleAsClass($sql, [$userEmail], 'App\Entity\User');
            if ($result === null) {
                return null; // Return null when no result is found.
            }
            $this->user = $result;

            $fileModel = new FileModel();

            if (is_null($this->user->getAvatarId()) === false) {
                $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
            } else {
                $this->user->setAvatarFile(new File());
            }
            return $this->user;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Create a user providing a user object
     * @param User $user
     * @return int|null
     */
    public function createUser(User $user): int|null
    {
        $sql = 'INSERT INTO user (avatar_id, pseudo, pass, email, role) VALUES (?, ?, ?, ?, ?)';

        $createdUserId = $this->insert(
            $sql,
            [
                null,
                $user->getPseudo(),
                $user->getPass(),
                $user->getEmail(),
                $user->getRole()
            ]
        );
        return $createdUserId;
    }

    /**
     * Updates a user in database
     * @param User $user
     * @return int|null
     */
    public function updateUser(User $user): int|null
    {
        if ($this->userExistsById($user->getId()) === false) {
            return null;
        }

        $sql = 'UPDATE user SET avatar_id=?, pseudo=?, pass=?, email=?, role=? WHERE id=?';
        return $this->update(
            $sql,
            [
                $user->getAvatarId(),
                $user->getPseudo(),
                $user->getPass(),
                $user->getEmail(),
                $user->getRole(),
                $user->getId()
            ]
        );
    }

    /**
     * Checks if a user exists in database based on its id
     * @param int $userId
     * @return bool
     */
    public function userExistsById(int $userId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE id = ?)";
        return $this->exists($sql, [$userId]);
    }

    /**
     * Checks if a user exists in database based on its pseudo
     * @param string $userPseudo
     * @return bool
     */
    public function userExistsByPseudo(string $userPseudo): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE pseudo = ?)";
        return $this->exists($sql, [$userPseudo]);
    }

    /**
     * Checks if a user exists in database based on its email
     * @param string $userEmail
     * @return bool
     */
    public function userExistsByEmail(string $userEmail): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE email = ?)";
        return $this->exists($sql, [$userEmail]);
    }

    /**
     * Checks if a user exists in database based on its email and password
     * @param string $userEmail
     * @param string $userPassword
     * @return bool
     */
    public function userExistsByEmailPassword(string $userEmail, string $userPassword): bool
    {
        $sql = 'SELECT EXISTS(SELECT * FROM user WHERE email = ? AND pass = ?)';
        return $this->exists($sql, [$userEmail, $userPassword]);
    }
}
