<?php

namespace App\Controller\Form;

use App\Controller\UserController;
use App\Entity\Res;
use App\Entity\User;
use App\Entity\UserOwner;
use Exception;

/**
 * Class FormUserProfile - Manage the user profile form.
 */
class FormUserProfile extends FormController
{
    /**
     * @var Res
     */
    private Res $res;

    /**
     * @var UserController
     */
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
        // -------------------------------------------------------------------- Checks.
        // User-pseudo : checks.
        $this->res = $this->checkPostedText('user-profile', 'pseudo', 4, 30);
        $this->res = $this->isUnique('user-profile', 'pseudo', $user->getUserId());

        // User-email : checks.
        $this->res = $this->checkPostedEmail('user-profile', 'email', 6, 254);
        $this->res = $this->isUnique('user-profile', 'email', $user->getUserId());

        // File-avatar : checks.
        $this->res = $this->checkPostedFileImg(
            'user-profile',
            'file-avatar',
            $this->sGlob->getFiles('file-avatar'),
            $user->getAvatarFile(),
            $this->getAvatarMaxSize()
        );

        // -------------------------------------------------------------------- Treatments.
        if ($this->res->isErr() === false) {
            // User avatar file : treatment.
            if ($this->sGlob->getFiles('file-avatar')['error'] === 0) {
                $resTreatFile = $this->treatFile($this->sGlob->getFiles('file-avatar'), 'avatar');

                if ($resTreatFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatFile->getMsg()['treat-file']);
                }

                $savedFile = $resTreatFile->getResult()['treat-file'];
                $user->setAvatarFile($savedFile);
                $user->setAvatarId($savedFile->getFileId());
            }

            // User simple fields : treatment.
            $user->setPseudo($this->sGlob->getPost('pseudo'));
            $user->setEmail($this->sGlob->getPost('email'));

            // User update.
            if ($this->res->isErr() === false) {
                $this->res = $this->userController->updateUser($user);
            }
        }
        return $this->res;
    }


    /**
     * Treats the user owner part of the form
     * @param UserOwner $userOwner
     * @return Res
     * @throws Exception
     */
    public function treatFormUserOwner(UserOwner $userOwner): Res
    {
        // -------------------------------------------------------------------- Checks.
        // Checks.
        $this->res = $this->checkPostedText('owner-profile', 'firstname', 4, 30);
        $this->res = $this->checkPostedText('owner-profile', 'lastname', 4, 30);
        $this->res = $this->checkPostedText('owner-profile', 'catchphrase', 4, 254);

        // File-cv : checks.
        $this->res = $this->checkPostedFileDoc(
            'owner-profile',
            'file-cv',
            $this->sGlob->getFiles('file-cv'),
            $userOwner->getCvFile(),
            $this->getCvMaxSize()
        );

        // File-photo : checks.
        $this->res = $this->checkPostedFileImg(
            'owner-profile',
            'file-photo',
            $this->sGlob->getFiles('file-photo'),
            $userOwner->getPhotoFile(),
            $this->getPhotoMaxSize()
        );

        // -------------------------------------------------------------------- Treatment.
        if ($this->res->isErr() === false) {
            // Owner simple fields : treatment.
            $userOwner->setFirstName($this->sGlob->getPost('firstname'));
            $userOwner->setLastName($this->sGlob->getPost('lastname'));
            $userOwner->setCatchPhrase($this->sGlob->getPost('catchphrase'));

            // Owner CV file : treatment.
            $cvFile = $this->sGlob->getFiles('file-cv');
            if ($cvFile['error'] === 0) {
                $resTreatCvFile = $this->treatFile($cvFile, 'cv');

                if ($resTreatCvFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatCvFile->getMsg()['treat-file']);
                }
                $savedCvFile = $resTreatCvFile->getResult()['treat-file'];
                $userOwner->setCvFile($savedCvFile);
                $userOwner->setCvFileId($savedCvFile->getFileId());
            }

            // Owner photo file : treatment.
            $photoFile = $this->sGlob->getFiles('file-photo');
            if ($photoFile['error'] === 0) {
                $resTreatPhotoFile = $this->treatFile($photoFile, 'photo');

                if ($resTreatPhotoFile->isErr() === true) {
                    $this->res->ko('user-profile', $resTreatPhotoFile->getMsg()['treat-file']);
                }

                $savedPhotoFile = $resTreatPhotoFile->getResult()['treat-file'];
                $userOwner->setPhotoFile($savedPhotoFile);
                $userOwner->setPhotoFileId($savedPhotoFile->getFileId());
            }

            // Owner update.
            if ($this->res->isErr() === false) {
                $this->res = $this->userController->updateUserOwner($userOwner);
            }
        }
        return $this->res;
    }


}
