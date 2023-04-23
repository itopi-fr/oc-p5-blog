<?php

namespace App\Model;

use App\Database\Connection;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserOwner;
use App\Model\UserModel;
use Exception;

class UserOwnerModel extends UserModel
{
    private UserOwner $owner;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getUserOwnerById($userOwnerId)
    {
        $sqlOwner = "SELECT * FROM user_owner_infos o INNER JOIN user u ON o.user_id = u.id WHERE u.id =?";
        $this->owner = $this->getSingleAsClass($sqlOwner, [$userOwnerId], 'App\Entity\UserOwner');

        $AvatarModel = new FileModel();
        if (is_null($this->owner->getAvatarId()) === false) {
            $this->owner->setAvatarFile($AvatarModel->getFileById($this->owner->getAvatarId()));
        } else {
            $this->owner->setAvatarFile(new File());
        }

        $CvModel = new FileModel();
        if (is_null($this->owner->getCvFileId()) === false) {
            $this->owner->setCvFile($CvModel->getFileById($this->owner->getCvFileId()));
        } else {
            $this->owner->setCvFile(new File());
        }

        $PhotoModel = new FileModel();
        if (is_null($this->owner->getPhotoFileId()) === false) {
            $this->owner->setPhotoFile($PhotoModel->getFileById($this->owner->getPhotoFileId()));
        } else {
            $this->owner->setPhotoFile(new File());
        }


        return $this->owner;
    }

    /**
     * Updates user_owner_infos in database
     * @param UserOwner $userOwner
     * @return int | Exception
     * @throws Exception
     */
    public function updateUserOwner(UserOwner $userOwner)
    {
        if (!$this->userOwnerExistsById($userOwner->getOwnerId())) {
            throw new Exception('Profil inconnu');
        }

        // TODO : Si l'user change de photo ou de CV, supprimer les anciens (fichiers + BDD).

        $sql = 'UPDATE user_owner_infos SET 
                            photo_file_id=?, 
                            cv_file_id=?, 
                            first_name=?, 
                            last_name=?, 
                            catch_phrase=? 
                        WHERE owner_id=?';

        return $this->update($sql, [
            $userOwner->getPhotoFileId(),
            $userOwner->getCvFileId(),
            $userOwner->getFirstName(),
            $userOwner->getLastName(),
            $userOwner->getCatchPhrase(),
            $userOwner->getOwnerId()
        ]);
    }

    public function userOwnerExistsById($userOwnerId)
    {
        $sql = "SELECT EXISTS(SELECT * FROM user_owner_infos WHERE owner_id = ?)";
        return $this->exists($sql, [$userOwnerId]);
    }
}