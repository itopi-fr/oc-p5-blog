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
    public function getUserById($id)
    {
        $sqlUser = "SELECT * FROM user WHERE id = " . $id;
        $this->user = $this->getSingleAsClass($sqlUser, 'App\Entity\User');
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
    public function getUserByEmail(string $email) {
        try {
            $sql = 'SELECT * FROM user WHERE email = "' . $email . '"';
            $result = $this->getSingleAsClass($sql, 'App\Entity\User');
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