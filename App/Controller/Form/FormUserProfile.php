<?php

namespace App\Controller\Form;

use App\Controller\UserController;
use App\Entity\Res;
use App\Entity\User;
use App\Entity\UserOwner;
use Exception;

class FormUserProfile extends FormController
{
    private Res $res;
    protected UserController $userController;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->userController = new UserController();
    }


    /**
     * Treats the user part of the form
     * @param User $user
     * @return Res
     * @throws Exception
     */
    public function treatFormUser(User $user): Res
    {
        // -------------------------------------------------------------------- Checks
        // user-pseudo : checks
        $this->res = $this->checkPostFieldText('user-profile', 'pseudo', 4, 30);
        $this->res = $this->isUserUnique('user-profile', 'pseudo', $user->getId());

        // user-email : checks
        $this->res = $this->checkPostFieldTextEmail('user-profile', 'email', 6, 254);
        $this->res = $this->isUserUnique('user-profile', 'email', $user->getId());

        // file-avatar : checks
        $this->res = $this->checkPostedFileImg(
            'user-profile',
            'file-avatar',
            $_FILES['file-avatar'],
            $user->getAvatarFile(),
            $this->getAvatarMaxSize()
        );

        // -------------------------------------------------------------------- Treatments
        if ($this->res->isErr() === false) {
            // User avatar file : treatment
            if ($_FILES['file-avatar']['error'] === 0) {
                $resTreatFile = $this->treatFile($_FILES['file-avatar'], 'avatar');

                if ($resTreatFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatFile->getMsg()['treat-file']);
                }

                $savedFile = $resTreatFile->getResult()['treat-file'];
                $user->setAvatarFile($savedFile);
                $user->setAvatarId($savedFile->getId());
            }

            // User simple fields : treatment
            $user->setPseudo($_POST['pseudo']);
            $user->setEmail($_POST['email']);

            // User update
            if ($this->res->isErr() === false) {
                $this->res = $this->userController->updateUser($user);
            }
        }
        return $this->res;
    }

    /**
     * @param UserOwner $userOwner
     * @return Res
     * @throws Exception
     */
    public function treatFormUserOwner(UserOwner $userOwner): Res
    {
        // -------------------------------------------------------------------- Checks
        // Checks
        $this->res = $this->checkPostFieldText('owner-profile', 'firstname', 4, 30);
        $this->res = $this->checkPostFieldText('owner-profile', 'lastname', 4, 30);
        $this->res = $this->checkPostFieldText('owner-profile', 'catchphrase', 4, 254);

        // file-cv : checks
        $this->res = $this->checkPostedFileDoc(
            'owner-profile',
            'file-cv',
            $_FILES['file-cv'],
            $userOwner->getCvFile(),
            $this->getCvMaxSize()
        );

        // file-photo : checks
        $this->res = $this->checkPostedFileImg(
            'owner-profile',
            'file-photo',
            $_FILES['file-photo'],
            $userOwner->getPhotoFile(),
            $this->getPhotoMaxSize()
        );

        // -------------------------------------------------------------------- Treatment
        if ($this->res->isErr() === false) {
            // Owner simple fields : treatment
            $userOwner->setFirstName($_POST['firstname']);
            $userOwner->setLastName($_POST['lastname']);
            $userOwner->setCatchPhrase($_POST['catchphrase']);

            // Owner CV file : treatment
            if ($_FILES['file-cv']['error'] === 0) {
                $resTreatCvFile = $this->treatFile($_FILES['file-cv'], 'cv');

                if ($resTreatCvFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatCvFile->getMsg()['treat-file']);
                }
                $savedCvFile = $resTreatCvFile->getResult()['treat-file'];

//                $savedCvFile = $this->treatFile($_FILES['file-cv'], 'cv');
                $userOwner->setCvFile($savedCvFile);
                $userOwner->setCvFileId($savedCvFile->getId());
            }

            // Owner photo file : treatment
            if ($_FILES['file-photo']['error'] === 0) {
                $resTreatPhotoFile = $this->treatFile($_FILES['file-photo'], 'photo');

                if ($resTreatPhotoFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatPhotoFile->getMsg()['treat-file']);
                }

                $savedPhotoFile = $resTreatPhotoFile->getResult()['treat-file'];

//                $savedPhotoFile = $this->treatFile($_FILES['file-photo'], 'photo');
                $userOwner->setPhotoFile($savedPhotoFile);
                $userOwner->setPhotoFileId($savedPhotoFile->getId());
            }

            // Owner update
            if ($this->res->isErr() === false) {
                $this->res = $this->userController->updateUserOwner($userOwner);
            }
        }
        return $this->res;
    }
}