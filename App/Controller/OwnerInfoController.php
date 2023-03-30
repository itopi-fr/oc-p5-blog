<?php

namespace App\Controller;

use App\Entity\OwnerInfo;
use App\Model\OwnerInfoModel;
use App\Entity\File;

class OwnerInfoController
{
    protected OwnerInfoModel $ownerInfoModel;
    protected OwnerInfo $ownerInfo;

    public function __construct()
    {
        $this->ownerInfoModel = new OwnerInfoModel();
        $this->ownerInfo = new OwnerInfo();
    }

    public function getOwnerInfo(): OwnerInfo
    {
        $ownerInfoObject = $this->ownerInfoModel->getOwnerInfo();
        $this->ownerInfo = $this->hydrateOwnerInfoObject($ownerInfoObject);
        return $this->ownerInfo;
    }

    public function hydrateOwnerInfoObject($ownerInfoObject): OwnerInfo
    {
        $photoFileCtrl = new FileController();
        $cvFileCtrl = new FileController();
        $ownerInfo = new OwnerInfo();

        $ownerInfo->setOwnerId($ownerInfoObject->owner_id);
        $ownerInfo->setFirstName($ownerInfoObject->first_name);
        $ownerInfo->setLastName($ownerInfoObject->last_name);
        $ownerInfo->setPhotoFileId($ownerInfoObject->photo_file_id);
        $ownerInfo->setPhotoFile($photoFileCtrl->getFileById($ownerInfoObject->photo_file_id));
        $ownerInfo->setCvFileId($ownerInfoObject->cv_file_id);
        $ownerInfo->setCvFile($cvFileCtrl->getFileById($ownerInfoObject->cv_file_id));
        $ownerInfo->setCatchPhrase($ownerInfoObject->catch_phrase);
        return $ownerInfo;
    }
}
