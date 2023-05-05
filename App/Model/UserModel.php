<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\User;
use App\Entity\File;
use Exception;

/**
 * Class UserModel - Manage the users in the database.
 */
class UserModel extends Connection
{
    /**
     * @var User
     */
    public User $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Checks if a value is unique in the user table - can be based on any field.
     * @param string $value - The value to check
     * @param string $field - The field to check
     * @param int $userId - The user id to exclude from the check
     * @return bool
     */
    public function isUnique(string $value, string $field, int $userId): bool
    {
        $sql = "SELECT * FROM user WHERE $field = ? AND user_id != ?";
        // TODO: replace by exists().
        $result = $this->getSingleAsClass($sql, [$value, $userId], 'App\Entity\User');
        return $result === null;
    }


    /**
     * Get all users. Returns an array of standard objects.
     * @return array
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM user WHERE role != 'owner' ORDER BY role";
        $allUsers = $this->getMultipleAsObjectsArray($sql, []);
        return $allUsers;
    }


    /**
     * Returns a user object based on its id.
     * @param int|null $userId - The user id
     * @return User|null
     */
    public function getUserById(int|null $userId): User|null
    {
        if ($userId === null) {
            return null;
        }

        $sqlUser = "SELECT * FROM user WHERE user_id =?";
        $this->user = $this->getSingleAsClass($sqlUser, [$userId], 'App\Entity\User');

        $fileModel = new FileModel();
        $this->user->setAvatarFile(new File());

        if ($this->user->getAvatarId() !== null) {
            $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
        }
        return $this->user;
    }


    /**
     * Returns a user object based on its email.
     * @param string $userEmail - The user email
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

            if ($this->user->getAvatarId() !== null) {
                $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
            } else {
                $this->user->setAvatarFile(new File());
            }
            return $this->user;
        } catch (Exception $exception) {
            return null;
        }
    }


    /**
     * Create a user providing a user object
     * @param User $user - The user object
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
     * @param User $user - The user object
     * @return int|null
     */
    public function updateUser(User $user): int|null
    {
        if ($this->userExistsById($user->getUserId()) === false) {
            return null;
        }

        $sql = 'UPDATE user SET avatar_id=?, pseudo=?, pass=?, email=?, role=? WHERE user_id=?';
        return $this->update(
            $sql,
            [
                $user->getAvatarId(),
                $user->getPseudo(),
                $user->getPass(),
                $user->getEmail(),
                $user->getRole(),
                $user->getUserId()
            ]
        );
    }


    /**
     * Checks if a user exists in database based on its id
     * @param int $userId - The user id
     * @return bool
     */
    public function userExistsById(int $userId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE user_id = ?)";
        return $this->exists($sql, [$userId]);
    }


    /**
     * Checks if a user exists in database based on its pseudo
     * @param string $userPseudo - The user pseudo
     * @return bool
     */
    public function userExistsByPseudo(string $userPseudo): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE pseudo = ?)";
        return $this->exists($sql, [$userPseudo]);
    }


    /**
     * Checks if a user exists in database based on its email
     * @param string $userEmail - The user email
     * @return bool
     */
    public function userExistsByEmail(string $userEmail): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE email = ?)";
        return $this->exists($sql, [$userEmail]);
    }


    /**
     * Checks if a user exists in database based on its email and password
     * @param string $userEmail - The user email
     * @param string $userPassword - The user password
     * @return bool
     */
    public function userExistsByEmailPassword(string $userEmail, string $userPassword): bool
    {
        $sql = 'SELECT EXISTS(SELECT * FROM user WHERE email = ? AND pass = ?)';
        return $this->exists($sql, [$userEmail, $userPassword]);
    }
}
