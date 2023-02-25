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






}