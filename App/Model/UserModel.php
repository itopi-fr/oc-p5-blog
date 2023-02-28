<?php

namespace App\Model;


use App\Database\Connection;
use App\Entity\User;
use App\Entity\File;

class UserModel extends Connection
{
    public User $user;
    public function __construct()
    {
        parent::__construct();
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
        catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

}