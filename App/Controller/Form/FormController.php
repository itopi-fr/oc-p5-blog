<?php

namespace App\Controller\Form;

use App\Controller\MainController;
use App\Entity\File;

class FormController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function formIndex(string $formName)
    {
        if ($formName === 'form-user-profile') {
            $formUserProfile = new FormUserProfile();
            $formUserProfile->treatForm();
        }
    }
}