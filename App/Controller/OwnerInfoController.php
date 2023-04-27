<?php

namespace App\Controller;

use App\Entity\OwnerInfo;
use App\Model\OwnerInfoModel;
use App\Entity\File;

/**
 * Class OwnerInfoController - Owner info functions.
 * Read only owner info displayed in the header
 */
class OwnerInfoController
{
    /**
     * @var OwnerInfoModel
     */
    protected OwnerInfoModel $ownerInfoModel;

    /**
     * @var OwnerInfo
     */
    protected OwnerInfo $ownerInfo;

    /**
     * @var MainController
     */
    protected MainController $mainCtr;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ownerInfoModel = new OwnerInfoModel();
        $this->ownerInfo = new OwnerInfo();
    }

    /**
     * Supposing that there is only one owner, get his info
     * @return OwnerInfo
     */
    public function getOwnerInfo(): OwnerInfo
    {
        $ownerInfoObject = $this->ownerInfoModel->getOwnerInfo();
        $this->ownerInfo = $this->hydrateOwnerInfoObject($ownerInfoObject);
        return $this->ownerInfo;
    }

    /**
     * Hydrate a proper OwnerInfo object with data from database
     * @param $ownerInfoObject
     * @return OwnerInfo
     */
    public function hydrateOwnerInfoObject($ownerInfoObject): OwnerInfo
    {
        $ownerInfo = new OwnerInfo();
        $photoFileCtrl = new FileController();
        $cvFileCtrl = new FileController();

        // Owner id.
        $ownerInfo->setOwnerId($ownerInfoObject->owner_id);

        // First name.
        if ($ownerInfoObject->first_name === null) {
            $ownerInfo->setFirstName('');
        } else {
            $ownerInfo->setFirstName($ownerInfoObject->first_name);
        }

        // Last name.
        if ($ownerInfoObject->last_name == null) {
            $ownerInfo->setLastName('');
        } else {
            $ownerInfo->setLastName($ownerInfoObject->last_name);
        }

        // Photo file.
        if ($ownerInfoObject->photo_file_id == null) {
            $ownerInfo->setPhotoFileId(0);
            $ownerInfo->setPhotoFile(new File());
        } else {
            $ownerInfo->setPhotoFileId($ownerInfoObject->photo_file_id);
            $ownerInfo->setPhotoFile($photoFileCtrl->getFileById($ownerInfoObject->photo_file_id));
        }

        // CV file.
        if ($ownerInfoObject->cv_file_id == null) {
            $ownerInfo->setCvFileId(0);
            $ownerInfo->setCvFile(new File());
        } else {
            $ownerInfo->setCvFileId($ownerInfoObject->cv_file_id);
            $ownerInfo->setCvFile($cvFileCtrl->getFileById($ownerInfoObject->cv_file_id));
        }

        // Catch phrase.
        if ($ownerInfoObject->catch_phrase == null) {
            $ownerInfo->setCatchPhrase('');
        } else {
            $ownerInfo->setCatchPhrase($ownerInfoObject->catch_phrase);
        }

        return $ownerInfo;
    }
}
