<?php

namespace App\Controller\Form;

use App\Entity\File;
use App\Controller\FileController;

class FormUserProfile extends FormController
{
    private array $return = [];

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Treats the form
     * @return array
     */
    public function treatForm()
    {
        // file-avatar : checks
        if (empty($_FILES['file-avatar']) ||
            empty($_FILES['file-avatar']['name']) ||
            empty($_FILES['file-avatar']['type']) ||
            empty($_FILES['file-avatar']['tmp_name']) ||
            empty($_FILES['file-avatar']['size'])) {
            $this->return['err'] = true;
            $this->return['msg'] = 'Avatar : Veuillez renseigner un fichier';
            return $this->return;
        }
        if (!$this->checkFileSize($_FILES['file-avatar'], $this->getAvatarMaxSize())) {
            $this->return['err'] = true;
            $this->return['msg'] = 'Avatar : le fichier est trop volumineux';
            return $this->return;
        }
        if (!$this->checkFileIsImage($_FILES['file-avatar'])) {
            $this->return['err'] = true;
            $this->return['msg'] = 'Avatar : le fichier n\'est pas une image';
            return $this->return;
        }
        // file-avatar : treatment
        $savedFile = $this->treatFile($_FILES['file-avatar'], 'avatar');
        $this->return['msg'] = 'Votre profil a bien été mis à jour';
        $this->return['avatar'] = $savedFile;
        return $this->return;


    }


}