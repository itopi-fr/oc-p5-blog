<?php

namespace App\Controller;

use App\Controller\Form\FormController;
use App\Controller\Form\FormLogInOutReg;
use App\Controller\Form\FormUserChangePass;
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
    public UserModel $userModel;
    public User $user;
    public $userAvatar;

    public UserOwner $userOwner;
    public Token $token;
    public TokenController $tokenController;
    protected UserOwnerModel $userOwnerModel;

    public function __construct()
    {
        parent::__construct();
        $this->tokenController = new TokenController();
        $this->userModel = new UserModel();
    }

    public function index($userAction)
    {
        isset($_SESSION['userid']) ? $userId = $_SESSION['userid'] : $userId = -1;

        // Form Login
        if ($userAction == 'connexion' && isset($_POST["submit-connect"])) {
            $this->twigData['result'] = (new FormLogInOutReg())->login($_POST['email'], $_POST['pass']);
        }

        if ($userId > -1) {
            $this->user = $this->userModel->getUserById($userId);

            // Owner
            if($this->user->getRole() == 'owner') {
                $this->userOwner = (new UserOwnerModel())->getUserOwnerById($this->user->getId());

                // Form Owner
                if ($userAction == 'profil' && isset($_POST["submit-owner-profile"])) {
                    $this->twigData['result'] = (new FormUserProfile())->treatFormUserOwner($this->userOwner);
                }
                $this->twigData['owner'] = $this->userOwner;
            }


            // Form User Profile
            if ($userAction == 'profil' && isset($_POST["submit-user-profile"])) {
                $this->twigData['result'] = (new FormUserProfile())->treatFormUser($this->user);
            }

            // Form User Change Password
            if ($userAction == 'profil' && isset($_POST["submit-user-pass"])) {
                $this->twigData['result'] = (new FormUserChangePass())->treatFormChangePass($this->user, $_POST["pass-old"], $_POST["pass-new-a"], $_POST["pass-new-b"]);
            }

            // Form Logout
            if ($userAction == 'deconnexion') {
                $this->twigData['result'] = (new FormLogInOutReg())->logout();
            }

            // User
            $this->twigData['user'] = $this->user;
        }

        // Affichage page
        echo $this->twig->render("pages/bo/page_bo_user.twig", $this->twigData);
    }


    public function updateUser(User $user)
    {
        return $this->userModel->updateUser($user);
    }



    public function updateUserOwner(UserOwner $userOwner)
    {
        $this->userOwnerModel = new UserOwnerModel();
        return $this->userOwnerModel->updateUserOwner($userOwner);
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


    public function test($userAction)
    {
        // ----------------------------------- File -----------------------------------
        // Test Get File by id
        if (isset($_POST['test-get-file-by-id']) && !empty($_POST['test-get-file-by-id'])) {
            $id = $_POST['test-get-file-by-id'];
            $fileController = new FileController();
            $this->twigData['test_get_file_by_id'] = $fileController->getFileById($id);
        }

        // Test Delete File by id
        if (isset($_POST['test-delete-file-id']) && !empty($_POST['test-delete-file-id'])) {
            $id = $_POST['test-delete-file-id'];
            $fileController = new FileController();
            $this->twigData['test_delete_file_by_id'] = $fileController->deleteFileById($id);
        }

        // ----------------------------------- User -----------------------------------
        // Test Get User
        if ($userAction === 'test-get-user-1') {
            $userModel = new UserModel();
            $this->dump($userModel->getUserById(1));
        }

        // Test UserOwner
        if ($userAction === 'test-get-user-owner-1') {
            $userOwnerModel = new UserOwnerModel();
            $this->dump($userOwnerModel->getUserOwnerById(1));
        }

        // ----------------------------------- Token -----------------------------------
        // Test createPassChangeToken
        if($userAction === 'test-create-pass-change-token') {
            $this->tokenController = new \App\Controller\TokenController();
            $this->dump($this->tokenController->createPassChangeToken(1));
        }

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





        // Twig
        echo match ($userAction) {
            'home'          => $this->twig->render("pages/bo/bo_test.twig",        $this->twigData),
            default         => $this->twig->render("pages/fo/fo_error.twig",            $this->twigData),
        };
    }



}
