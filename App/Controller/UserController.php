<?php

namespace App\Controller;

use App\Controller\Form\FormLogInOutReg;
use App\Controller\Form\FormUserChangePass;
use App\Controller\Form\FormUserProfile;
use App\Entity\Res;
use App\Entity\Token;
use App\Entity\UserOwner;
use App\Model\UserOwnerModel;
use App\Entity\User;
use App\Model\UserModel;
use Exception;

class UserController extends MainController
{
    protected Res $res;
    protected UserModel $userModel;

    protected User $user;

    protected UserOwner $userOwner;

    protected Token $token;
    protected TokenController $tokenController;
    protected UserOwnerModel $userOwnerModel;

    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->tokenController = new TokenController();
        $this->userModel = new UserModel();
    }


    public function index($userAction, $userActionSub = null)
    {
        $this->dump($userAction);

        if ($userAction === 'inscription') {
            // Form Register
            if (isset($_POST["submit-register"])) {
                $this->twigData['result'] = (new FormLogInOutReg())->register(
                    $_POST['pseudo'],
                    $_POST['email'],
                    $_POST['pass'],
                    $_POST['pass-confirm']
                );
            }

            // Display page
            echo $this->twig->render("pages/bo/page_bo_register.twig", $this->twigData);
        }

        if ($userAction === 'activation') {
            $this->dump($userActionSub);
            if (isset($userActionSub)) {
                // /user/activation?token=123456789
                $this->token = $this->tokenController->getToken($userActionSub);
                if ($this->token->getId() > -1) {
                    $this->user = $this->userModel->getUserById($this->token->getUserId());
                    if ($this->user->getId() > -1) {
                        $this->user->setRole('user');
                        $this->userModel->updateUser($this->user);
                        $this->tokenController->deleteTokenById($this->token->getId());
                        $this->res->ok('activation', 'Votre compte a bien été activé', null);
                        $this->twigData['result'] = $this->res;
                        echo $this->twig->render("pages/bo/page_bo_activate.twig", $this->twigData);
                        $this->redirectTo('/user/profil', 3);
                    }
                }
            }
        }

        isset($_SESSION['userid']) ? $userId = $_SESSION['userid'] : $userId = -1;

        // Form Login
        if ($userAction == 'connexion' && isset($_POST["submit-connect"])) {
            $this->twigData['result'] = (new FormLogInOutReg())->login($_POST['email'], $_POST['pass']);
            $this->refresh();
        }

        if ($userId > -1) {
            $this->user = $this->userModel->getUserById($userId);

            // Owner
            if ($this->user->getRole() == 'owner') {
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
                $this->twigData['result'] = (new FormUserChangePass())->treatFormChangePass(
                    $this->user,
                    $_POST["pass-old"],
                    $_POST["pass-new-a"],
                    $_POST["pass-new-b"]
                );
            }

            // Form Logout
            if ($userAction == 'deconnexion') {
                $this->twigData['result'] = (new FormLogInOutReg())->logout();
                $this->refreshNow();
            }

            // User
            $this->twigData['user'] = $this->user;
        }

        // Display page
        echo $this->twig->render("pages/bo/page_bo_user.twig", $this->twigData);
    }

    /**
     * Create a user providing a pseudo, an email and a password
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @return Exception|User
     */
    public function regCreateUser(string $pseudo, string $email, string $password): Exception|User
    {
        $this->res = new Res();
        $this->user = new User();
        $this->user->setPseudo($pseudo);
        $this->user->setEmail($email);
        $this->user->setPass($password);
        $this->user->setAvatarId(-1);
        $this->user->setRole('user-validation-waiting');

        $userCreatedId = $this->userModel->createUser($this->user);

        if ($userCreatedId > -1) {
            $this->user = $this->userModel->getUserById($userCreatedId);
            $tokenContent = $this->tokenController->createUserToken($this->user->getId(), 'user-validation');
        }

        // TODO : send mail with $tokenContent

        return $this->user;
    }


    /**
     * Updates a user providing a user object
     * @param User $user
     * @return Exception|int
     * @throws Exception
     */
    public function updateUser(User $user): Exception|int
    {
        return $this->userModel->updateUser($user);
    }


    /**
     * @param UserOwner $userOwner
     * @return Exception|int
     * @throws Exception
     */
    public function updateUserOwner(UserOwner $userOwner): Exception|int
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
}
