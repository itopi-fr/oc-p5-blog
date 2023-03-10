<?php

namespace App\Controller\Form;

use App\Controller\UserController;
use App\Entity\File;
use App\Controller\FileController;
use App\Entity\Res;
use App\Entity\User;
use App\Model\UserModel;

class FormUserProfile extends FormController
{
    private array $return = [];
    private Res $res;
    private UserController $userController;

    public function __construct()
    {
        parent::__construct();
        $this->userController = new UserController();
    }


    /**
     * Treats the form
     * @param User $user
     * @return Res
     */
    public function treatForm(User $user): Res
    {
        $this->res = new Res();

        // ------------------------------------------------------------------------------------------------------ Checks
        // user-pseudo : checks
        if (!$this->isSet($_POST['pseudo'])) {
            $this->res->ko('pseudo', 'Veuillez renseigner un pseudo');
        }
        else if (!$this->isUnique($_POST['pseudo'], 'pseudo', $user->getId())) {
            $this->res->ko('pseudo', 'Ce pseudo est déjà utilisé');
        }
        else if (!$this->isAlphaNumPlus($_POST['pseudo'])) {
            $this->res->ko('pseudo', 'Le pseudo ne doit contenir que des lettres, des chiffres, des tirets ou des underscores');
        }
        else if (!$this->isBetween($_POST['pseudo'], 4, 20)) {
            $this->res->ko('pseudo', 'Le pseudo doit contenir entre 4 et 20 caractères');
        }

        // user-email : checks
        if (!$this->isSet($_POST['email'])) {
            $this->res->ko('email', 'non renseigné');
        }
        else if (!$this->isUnique($_POST['email'], 'email', $user->getId())) {
            $this->res->ko('email', 'déjà utilisé');
        }
        else if (!$this->isEmail($_POST['email'])) {
            $this->res->ko('email', 'format non valide');
        }
        else if (!$this->isBetween($_POST['email'], 4, 50)) {
            $this->res->ko('email', 'doit contenir entre 4 et 50 caractères');
        }

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


}