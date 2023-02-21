<?php

namespace App\Controller;

class UserController extends MainController
{
    private array $twigData = ['data1' => 'données 1', 'data2' => 'données 2'];

    public function index($userAction)
    {
        $this->initTwig();

        echo match ($userAction) {
            'connexion'     => $this->twig->render("pages/bo/bo_user_login.twig", $this->twigData),
            'deconnexion'   => $this->twig->render("pages/bo/bo_user_logout.twig", $this->twigData),
            'inscription'   => $this->twig->render("pages/bo/bo_user_register.twig", $this->twigData),
            'profil'        => $this->twig->render("pages/bo/bo_user_profile.twig", $this->twigData),
            default         => $this->twig->render("pages/fo/fo_error.twig", $this->twigData),
        };

    }
}
