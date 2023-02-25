<?php

namespace App\Controller;

use App\Entity\Token;
use App\Model\TokenModel;
use App\Entity\UserOwner;
use App\Model\UserOwnerModel;
use App\Entity\User;
use App\Model\UserModel;
use stdClass;


class UserController extends MainController
{
    public User $user;
    public UserOwner $userOwner;
    public Token $token;

    public function __construct()
    {
        parent::__construct();
    }

    public function index($userAction)
    {
        // Test User
//        $userModel = new UserModel();
//        $this->user = $userModel->getUserById(1);
//        $this->twigData['user'] = $this->user;
//        $this->dump($this->user);

        // Test UserOwner
//        $userOwnerModel = new UserOwnerModel();
//        $this->userOwner = $userOwnerModel->getUserOwnerById(1);
//        $this->twigData['user'] = $this->userOwner;
//        $this->dump($this->userOwner);

        // Test GetToken
//        $tokenModel = new TokenModel();
//        $this->token = $tokenModel->getTokenById(1);
//        $this->dump($this->token);

        // Test CreateToken
        $token = new Token();
        $token->createPassChangeToken(1);
        $tokenModel = new TokenModel();
        $this->dump($tokenModel->insertPassChangeToken($token));

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

    public function login()
    {
        // Vérifier tokens et supprimer les anciens
    }

    public function logout()
    {
        // Vérifier tokens et supprimer les anciens
    }

    public function register()
    {

    }

    public function resetPassword()
    {
        // Vérifier tokens, s'il y en a un de valide, le renvoyer
    }






}
