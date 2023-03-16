<?php

namespace App\Controller\Form;

use App\Controller\UserController;
use App\Entity\Res;
use App\Entity\User;
use App\Entity\UserOwner;

class FormUserProfile extends FormController
{
    private Res $res;
    private UserController $userController;

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
     */
    public function treatFormUser(User $user): Res
    {


        // ------------------------------------------------------------------------------------------------------ Checks
        // user-pseudo : checks
        $this->res = $this->checkPostFieldText('pseudo', 4, 30);
        $this->res = $this->checkIfUnique('pseudo', $user->getId());

        // user-email : checks
        $this->res = $this->checkPostFieldTextEmail('email', 6, 254);
        $this->res = $this->checkIfUnique('email', $user->getId());

        // file-avatar : checks
        if (!$this->fileIsSent($_FILES['file-avatar']) && !is_a($user->getAvatarFile(), 'App\Entity\File') ) {
            $this->res->ko('avatar', 'Veuillez renseigner un fichier');
        }
        else if ($this->fileIsSent($_FILES['file-avatar']) ) {
            if (!$this->checkFileIsImage($_FILES['file-avatar'])) {
                $this->res->ko('avatar', 'le fichier n\'est pas une image');
            }
            if (!$this->checkFileSize($_FILES['file-avatar'], $this->getAvatarMaxSize())) {
                $this->res->ko('avatar', 'le fichier est trop volumineux');
            }
        }

        $this->res->setType('profil');
        if ($this->res->isErr()) return $this->res;

        // -------------------------------------------------------------------------------------------------- Treatment
        // User avatar file : treatment
        if ($this->fileIsSent($_FILES['file-avatar'])) {
            $savedFile = $this->treatFile($_FILES['file-avatar'], 'avatar');
            $user->setAvatarFile($savedFile);
            $user->setAvatarId($savedFile->getId());
        }

        // User simple fields : treatment
        $user->setPseudo($_POST['pseudo']);
        $user->setEmail($_POST['email']);

        // User update
        $result = $this->userController->updateUser($user);

        if ($result === 0) {
            $this->res->ok('profil', 'Aucun changement apporté au profil', null);
        } else if ($result === 1) {
            $this->res->ok('profil', 'Le profil a bien été mis à jour', null);
        } else {
            $this->res->ko('profil', 'Une erreur est survenue');
        }

        return $this->res;
    }

    public function treatFormUserOwner(UserOwner $userOwner): Res
    {
        // Checks
        $this->res = $this->checkPostFieldText('firstname', 4, 30);
        $this->res = $this->checkPostFieldText('lastname', 4, 30);
        $this->res = $this->checkPostFieldTextarea('catchphrase', 4, 254);
        $this->res->setType('auteur');

        // file-cv : checks
        if (!$this->fileIsSent($_FILES['file-cv']) && !is_a($userOwner->getCvFile(), 'App\Entity\File') ) {
            $this->res->ko('cv', 'Veuillez renseigner un fichier');
        }
        else if ($this->fileIsSent($_FILES['file-cv']) ) {
            if (!$this->checkFileIsDoc($_FILES['file-cv'])) {
                $this->res->ko('cv', 'le fichier n\'est pas un pdf');
            }
            if (!$this->checkFileSize($_FILES['file-cv'], $this->getCvMaxSize())) {
                $this->res->ko('cv', 'le fichier est trop volumineux');
            }
        }

        // file-photo : checks
        if (!$this->fileIsSent($_FILES['file-photo']) && !is_a($userOwner->getPhotoFile(), 'App\Entity\File') ) {
            $this->res->ko('photo', 'Veuillez renseigner un fichier');
        }
        else if ($this->fileIsSent($_FILES['file-photo']) ) {
            if (!$this->checkFileIsImage($_FILES['file-photo'])) {
                $this->res->ko('photo', 'le fichier n\'est pas une image');
            }
            if (!$this->checkFileSize($_FILES['file-photo'], $this->getPhotoMaxSize())) {
                $this->res->ko('photo', 'le fichier est trop volumineux');
            }
        }


        if ($this->res->isErr()) return $this->res;

        // Owner simple fields : treatment
        $userOwner->setFirstName($_POST['firstname']);
        $userOwner->setLastName($_POST['lastname']);
        $userOwner->setCatchPhrase($_POST['catchphrase']);

        // Owner CV file : treatment
        if ($this->fileIsSent($_FILES['file-cv'])) {
            $savedCvFile = $this->treatFile($_FILES['file-cv'], 'cv');
            $userOwner->setCvFile($savedCvFile);
            $userOwner->setCvFileId($savedCvFile->getId());
        }

        // Owner photo file : treatment
        if ($this->fileIsSent($_FILES['file-photo'])) {
            $savedPhotoFile = $this->treatFile($_FILES['file-photo'], 'photo');
            $userOwner->setPhotoFile($savedPhotoFile);
            $userOwner->setPhotoFileId($savedPhotoFile->getId());
        }


        // User update
        $result = $this->userController->updateUserOwner($userOwner);

        if ($result === 0) {
            $this->res->ok('auteur', 'Aucun changement apporté aux informations de l\'auteur', null);
        } else if ($result === 1) {
            $this->res->ok('auteur', 'Les informations de l\'auteur ont bien été mises à jour', null);
        } else {
            $this->res->ko('auteur', 'Une erreur est survenue');
        }
        return $this->res;
    }
}