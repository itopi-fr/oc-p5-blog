<?php

namespace App\Controller;

use App\Entity\UserOwner;
use App\Model\UserOwnerModel;
use App\Entity\User;
use App\Model\UserModel;


class UserController extends MainController
{
    public User $user;
    public UserOwner $userOwner;
    private array $twigData = ['data1' => 'données 1', 'data2' => 'données 2'];


    public function index($userAction)
    {
        $this->initTwig();

        // Test User
//        $userModel = new UserModel();
//        $this->user = $userModel->getUserById(1);
//        $this->twigData['user'] = $this->user;
//        $this->dump($this->user);

        // Test UserOwner
        $userOwnerModel = new UserOwnerModel();
        $this->userOwner = $userOwnerModel->getUserOwnerById(1);
        $this->twigData['user'] = $this->userOwner;
        $this->dump($this->userOwner);

        // Routing
        echo match ($userAction) {
            'home'          => $this->twig->render("pages/bo/bo_user_home.twig", $this->twigData),
            'connexion'     => $this->twig->render("pages/bo/bo_user_login.twig", $this->twigData),
            'deconnexion'   => $this->twig->render("pages/bo/bo_user_logout.twig", $this->twigData),
            'inscription'   => $this->twig->render("pages/bo/bo_user_register.twig", $this->twigData),
            'profil'        => $this->twig->render("pages/bo/bo_user_profile.twig", $this->twigData),
            default         => $this->twig->render("pages/fo/fo_error.twig", $this->twigData),
        };
    }


}
