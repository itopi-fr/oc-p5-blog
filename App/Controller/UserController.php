<?php

namespace App\Controller;

use App\Controller\Form\FormController;
use App\Controller\Form\FormUserProfile;
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
        if ($userAction == 'profil' && isset($_FILES['file-avatar']) && !empty($_FILES['file-avatar'])) {
            $this->twigData['result'] = (new FormUserProfile())->treatForm();
        }



        /**
         * --------------------------------- Tests ---------------------------------
         */

        // Test Get File by id
        if ($userAction === 'test' && isset($_POST['test-get-file-by-id']) && !empty($_POST['test-get-file-by-id'])) {
            $id = $_POST['test-get-file-by-id'];
            $fileController = new FileController();
            $this->twigData['test_get_file_by_id'] = $fileController->getFileById($id);
        }

        // Test Delete File by id
        if ($userAction === 'test' && isset($_POST['test-delete-file-id']) && !empty($_POST['test-delete-file-id'])) {
            $id = $_POST['test-delete-file-id'];
            $fileController = new FileController();
            $this->twigData['test_delete_file_by_id'] = $fileController->deleteFileById($id);
        }


        // Test User
        if ($userAction === 'test-get-user-1') {
            $userModel = new UserModel();
            $this->dump($userModel->getUserById(1));
        }

        // Test UserOwner
        if ($userAction === 'test-get-user-owner-1') {
            $userOwnerModel = new UserOwnerModel();
            $this->dump($userOwnerModel->getUserOwnerById(1));
        }


        // Test createPassChangeToken
//        if($userAction === 'test-create-pass-change-token') {
//            $this->tokenController = new TokenController();
//            $this->dump($this->tokenController->createPassChangeToken(1));
//        }

        // Test GetTokenByContent
//        $this->tokenController = new TokenController();
//        $this->dump($this->tokenController->getToken("681f8e0fd567bdf78f1f281ccbd6ed98bf69313c4da87c34a55a6026bc1db8c1"));

        // Test getUserTokens
//        $this->dump($this->tokenController->getUserTokens(1));

        // Test getLastValidTokenByUserId
//        $this->dump($this->tokenController->getLastValidTokenByUserId(1));

        // Test DeleteTokenById
//        $this->dump($this->tokenController->deleteTokenById(23));

        // Test DeleteExpiredToken
//        $this->dump($this->tokenController->deleteExpiredTokens(1));

        // Test VerifyToken
//        $this->dump($this->tokenController->verifyPassChangeToken('1a5f2fd46b14708ab16eee6b29cc49fd205428da93313804168b791644fc06b3', 'owner@test.fr'));

//        $this->dump($this->twigData);

        // Twig
        echo match ($userAction) {
            'home'          => $this->twig->render("pages/bo/bo_user_home.twig",        $this->twigData),
            'connexion'     => $this->twig->render("pages/bo/bo_user_login.twig",       $this->twigData),
            'deconnexion'   => $this->twig->render("pages/bo/bo_user_logout.twig",      $this->twigData),
            'inscription'   => $this->twig->render("pages/bo/bo_user_register.twig",    $this->twigData),
            'profil'        => $this->twig->render("pages/bo/bo_user_profile.twig",     $this->twigData),
            'test'          => $this->twig->render("pages/bo/bo_user_test.twig",        $this->twigData),
            default         => $this->twig->render("pages/fo/fo_error.twig",            $this->twigData),
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
