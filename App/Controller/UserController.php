<?php

namespace App\Controller;

use App\Entity\Token;
use App\controller\TokenController;
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
    public TokenController $tokenController;

    public function __construct()
    {
        parent::__construct();
        $this->tokenController = new TokenController();
    }

    public function index($userAction)
    {
        // Test User
//        $userModel = new UserModel();
//        $this->dump($userModel->getUserById(1));

        // Test UserOwner
//        $userOwnerModel = new UserOwnerModel();
//        $this->dump($userOwnerModel->getUserOwnerById(1));

        // Test createPassChangeToken
//        $this->dump($this->tokenController->createPassChangeToken(1));

        // Test GetToken
//        $this->tokenController = new TokenController();
        $this->dump($this->tokenController->getToken(22));

        // Test getUserTokens
//        $this->dump($this->tokenController->getUserTokens(1));

        // Test DeleteTokenById
//        $this->dump($this->tokenController->deleteTokenById(21));

        // Test DeleteExpiredToken
//        $this->dump($this->tokenController->deleteExpiredToken(1));

        // Test VerifyToken
//        $this->dump($this->tokenController->verifyPassChangeToken('0de18ad264324a8358db3aa69932410eea7877ddfc06cd934ad3cf6405c9480e', 'owner@test.fr'));

        // Test getLastValidTokenByUserId
//        $this->dump($this->tokenController->getLastValidTokenByUserId(1));


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
