<?php

namespace App\Model;

use App\Entity\File;
use App\Entity\UserOwner;
use Exception;

/**
 * Class UserOwnerModel - Manages the user owners in the database
 */
class UserOwnerModel extends UserModel
{
    /**
     * @var UserOwner
     */
    private UserOwner $owner;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Returns a user owner object based on its id.
     *
     * @param $userOwnerId - The id of the user owner to get
     * @return UserOwner
     */
    public function getUserOwnerById($userOwnerId): UserOwner
    {
        $sqlOwner = "SELECT * FROM user_owner_infos o INNER JOIN user u ON o.user_id = u.user_id WHERE u.user_id =?";
        $this->owner = $this->getSingleAsClass($sqlOwner, [$userOwnerId], 'App\Entity\UserOwner');

        $AvatarModel = new FileModel();
        if ($this->owner->getAvatarId() !== null) {
            $this->owner->setAvatarFile($AvatarModel->getFileById($this->owner->getAvatarId()));
        } else {
            $this->owner->setAvatarFile(new File());
        }

        $CvModel = new FileModel();
        if ($this->owner->getCvFileId() !== null) {
            $this->owner->setCvFile($CvModel->getFileById($this->owner->getCvFileId()));
        } else {
            $this->owner->setCvFile(new File());
        }

        $PhotoModel = new FileModel();
        if ($this->owner->getPhotoFileId() !== null) {
            $this->owner->setPhotoFile($PhotoModel->getFileById($this->owner->getPhotoFileId()));
        } else {
            $this->owner->setPhotoFile(new File());
        }


        return $this->owner;
    }


    /**
     * Updates user_owner_infos in database.
     *
     * @param UserOwner $userOwner - The user owner to update
     * @return int
     * @throws Exception
     */
    public function updateUserOwner(UserOwner $userOwner): int
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
                            catch_phrase=?,
                            sn_github=?,
                            sn_linkedin=?
                        WHERE owner_id=?';

        return $this->update($sql, [
            $userOwner->getPhotoFileId(),
            $userOwner->getCvFileId(),
            $userOwner->getFirstName(),
            $userOwner->getLastName(),
            $userOwner->getCatchPhrase(),
            $userOwner->getSnGithub(),
            $userOwner->getSnLinkedin(),
            $userOwner->getOwnerId()
        ]);
    }


    /**
     * Checks if a user owner exists in database.
     *
     * @param int $userOwnerId - The id of the user owner to check if it exists
     * @return bool
     */
    public function userOwnerExistsById(int $userOwnerId): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM user_owner_infos WHERE owner_id = ?)";
        return $this->exists($sql, [$userOwnerId]);
    }


}
