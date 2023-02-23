<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\User;
use App\Entity\File;

class UserModel
{
    private Connection $db;
    public User $user;
    public function __construct()
    {
        $this->db = new Connection();
    }


    public function getUserById($id)
    {
        $sqlUser = "SELECT * FROM user WHERE id = " . $id;
        $this->user = $this->db->getSingleAsClass($sqlUser, 'App\Entity\User');
        $fileModel = new FileModel();

        if (!is_null($this->user->getAvatarId())) {
            $this->user->setAvatarFile($fileModel->getFileById($this->user->getAvatarId()));
        } else {
            $this->user->setAvatarFile(new File());
        }

        return $this->user;
    }






}