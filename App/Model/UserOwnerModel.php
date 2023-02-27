<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserOwner;
use App\Model\UserModel;

class UserOwnerModel extends UserModel
{
    private UserOwner $owner;



    public function __construct()
    {
        parent::__construct();
    }

    public function getUserOwnerById($id)
    {
        $sqlOwner = "SELECT * FROM user_owner_infos o INNER JOIN user u ON o.user_id = u.id WHERE u.id = " . $id;
        $this->owner = $this->getSingleAsClass($sqlOwner, 'App\Entity\UserOwner');

        $AvatarModel = new FileModel();
        if (!is_null($this->owner->getAvatarId())) {
            $this->owner->setAvatarFile($AvatarModel->getFileById($this->owner->getAvatarId()));
        } else {
            $this->owner->setAvatarFile(new File());
        }

        $CvModel = new FileModel();
        if (!is_null($this->owner->getCvFileId())) {
            $this->owner->setCvFile($CvModel->getFileById($this->owner->getCvFileId()));
        } else {
            $this->owner->setCvFile(new File());
        }

        $PhotoModel = new FileModel();
        if (!is_null($this->owner->getPhotoFileId())) {
            $this->owner->setPhotoFile($PhotoModel->getFileById($this->owner->getPhotoFileId()));
        } else {
            $this->owner->setPhotoFile(new File());
        }


        return $this->owner;
    }
}