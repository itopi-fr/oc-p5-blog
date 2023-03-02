<?php

namespace App\Controller\Form;

use App\Entity\File;
use App\Controller\FileController;

class FormUserProfile extends FormController
{
    private mixed $email;
    private mixed $pseudo;
    private mixed $filePostedAvatar;
    private File $fileAvatar;
    private array $errors = [];








    public function __construct()
    {
        parent::__construct();
    }

    public function treatForm()
    {
        // Check if fields are empty
        if (empty($_FILES['file-avatar'])) $this->errors['file-avatar'] = 'Veuillez renseigner un fichier';


        // If no errors, treat the form
        if (empty($this->errors)) {
            $this->filePostedAvatar = $_FILES['file-avatar'];

            $fileAvatarCtrl = new FileController();
            $result = $fileAvatarCtrl->uploadFile($this->filePostedAvatar, 'avatar');
            $this->dump($result);

            if (!empty($result['file'])) {
                $this->fileAvatar = $result['file'];
            }

        }
    }

}