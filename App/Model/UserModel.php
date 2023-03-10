<?php

namespace App\Model;


use App\Database\Connection;
use App\Entity\User;
use App\Entity\File;
use Exception;

class UserModel extends Connection
{
    public User $user;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks if a value is unique in database
     * @param string $value
     * @param string $field
     * @param int $id
     * @return bool
     */
    public function isUnique(string $value, string $field, int $id = 0)
    {
        $sql = "SELECT * FROM user WHERE $field = ? AND id != ?";
        $result = $this->getSingleAsClass($sql, [$value, $id], 'App\Entity\User');
        return $result === null;
    }

    /**
     * Returns a user object based on its id.
     * @param int $id
     * @return User
     */
    public function getUserById($userId)
    {
        $sqlUser = "SELECT * FROM user WHERE id =?";
        $this->user = $this->getSingleAsClass($sqlUser, [$userId], 'App\Entity\User');
        $fileModel = new FileModel();

        if (!is_null($this->user->getAvatarId())) {
            $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
        } else {
            $this->user->setAvatarFile(new File());
        }

        return $this->user;
    }

    /**
     * Returns a user object based on its email.
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $userEmail) : null | User {
        try {
            $sql = 'SELECT * FROM user WHERE email =?';
            $result = $this->getSingleAsClass($sql, [$userEmail], 'App\Entity\User');
            if ($result === null) {
                return null; // Return null when no result is found
            }
            $this->user = $result;


            $fileModel = new FileModel();

            if (!is_null($this->user->getAvatarId())) {
                $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
            } else {
                $this->user->setAvatarFile(new File());
            }
            return $this->user;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Updates an user in database
     * @param User $user
     * @return int | Exception
     * @throws Exception
     */
    public function updateUser(User $user) {
        if (!$this->userExistsById($user->getId())) throw new Exception('Utilisateur inconnu');

        $sql = 'UPDATE user SET avatar_id=?, pseudo=?, pass=?, email=? WHERE id=?';
        return $this->update($sql, [$user->getAvatarId(), $user->getPseudo(), $user->getPass(), $user->getEmail(), $user->getId()]);
    }

    /**
     * Checks if a user exists in database based on its id
     * @param int $userId
     * @return null|object
     */
    public function userExistsById(int $userId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE id = ?)";
        return $this->exists($sql, [$userId]);
    }

}