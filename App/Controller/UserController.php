<?php

namespace App\Controller;

use App\Controller\Form\FormLogInOutReg;
use App\Controller\Form\FormUserChangePass;
use App\Controller\Form\FormUserProfile;
use App\Entity\Res;
use App\Entity\Token;
use App\Entity\UserOwner;
use App\Model\MailModel;
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


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->tokenController = new TokenController();
        $this->userModel = new UserModel();
    }


    public function index($userAction, $userActionSub = null)
    {
        // TODO: clean all this mess
        //  Distribute actions to separate methods : connect(), disconnect(), register(), etc.

        isset($_SESSION['userid']) === true ? $userId = $_SESSION['userid'] : $userId = null;

        // -------------------------------------------------------------------------------------------- user/inscription
        if ($userAction === 'inscription') {
            // Form Register
            if (isset($_POST["submit-register"]) === true) {
                $this->twigData['result'] = (new FormLogInOutReg())->register(
                    $_POST['pseudo'],
                    $_POST['email'],
                    $_POST['pass'],
                    $_POST['pass-confirm']
                );
            }

            // Display page
            echo $this->twig->render("pages/page_bo_register.twig", $this->twigData);
        }

        // --------------------------------------------------------------------------------------------- user/activation
        if ($userAction === 'activation') {
            if (isset($userActionSub) === true) {
                $this->userActivate($userActionSub);
            }
        }

        // ---------------------------------------------------------------------------------------------- user/connexion
        if ($userAction == 'connexion' && isset($_POST["submit-connect"]) === true) {
            $this->userLogin();
        }

        // ------------------------------------------------------------------------------------------------- user/profil
        if (is_null($userId) === false) {
            $this->user = $this->userModel->getUserById($userId);

            // Owner Profile
            if ($this->user->getRole() == 'owner') {
                $this->userOwner = (new UserOwnerModel())->getUserOwnerById($this->user->getId());

                if ($userAction == 'profil' && isset($_POST["submit-owner-profile"]) === true) {
                    $this->twigData['result'] = (new FormUserProfile())->treatFormUserOwner($this->userOwner);
                    $_SESSION['ownerinfo'] = (new OwnerInfoController())->getOwnerInfo();

                    // Refresh page if no error
                    if ($this->twigData['result']->isErr() === false) {
                        $this->refresh();
                    }

                }
                $this->twigData['owner'] = $this->userOwner;
            }

            // User Profile
            if ($userAction == 'profil' && isset($_POST["submit-user-profile"]) === true) {
                $this->twigData['result'] = (new FormUserProfile())->treatFormUser($this->user);
            }

            // Form User Change Password
            if ($userAction == 'profil' && isset($_POST["submit-user-pass"]) === true) {
                $this->twigData['result'] = (new FormUserChangePass())->treatFormChangePass(
                    $this->user,
                    $_POST["pass-old"],
                    $_POST["pass-new-a"],
                    $_POST["pass-new-b"]
                );
            }

            // Form Logout
            if ($userAction === 'deconnexion') {
                $this->dump('deconnexion');
                $this->twigData['result'] = (new FormLogInOutReg())->logout();
                $this->refresh(3);
            }

            // User
            $this->twigData['user'] = $this->user;
        }

        // Display page
        echo $this->twig->render("pages/page_bo_user.twig", $this->twigData);
    }

    public function userLogin()
    {
        $user = $this->getUserByEmail($_POST['email']);

        if ($user != null) {
            if ($user->getRole() == 'user-validation') {
                $this->res->ko('login', 'account-token-not-validated', null);
                $this->twigData['result'] = $this->res;
                return;
            }
            $this->twigData['result'] = (new FormLogInOutReg())->login($_POST['email'], $_POST['pass']);
            $this->refresh();
        } else {
            $this->res->ko('login', 'email-or-pass-incorrect', null);
            $this->twigData['result'] = $this->res;
        }
    }

    /**
     * Activate a user account. This method is called when a user click on the activation link in the email.
     * This email contains a link with a token : /user/activation/123456789
     * If the token is valid, not expired and the user exists, the user is activated and the token is deleted.
     * @return void
     * @throws Exception
     */
    public function userActivate($tokenContent)
    {
        $this->token = $this->tokenController->getToken($tokenContent);
        $tokenId = $this->token->getId();
        $this->user = $this->userModel->getUserById($this->token->getUserId());
        $this->user->setRole('user');

        if (is_int($this->userModel->updateUser($this->user)) === true) {
            $this->tokenController->deleteTokenById($this->token->getId());
            $this->res->ok('activation', 'account-activated', null);
            $this->twigData['result'] = $this->res;
            $this->redirectTo('/user/connexion', 3);
        } else {
            $this->res->ko('activation', 'account-activation-failed', null);
            $this->twigData['result'] = $this->res;
            return;
        }
    }

    /**
     * Get a user by its id
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return $this->userModel->getUserById($id);
    }

    /**
     * Get a user by its email
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        return $this->userModel->getUserByEmail($email);
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
        $this->user->setAvatarId(1); // default avatar
        $this->user->setRole('user-validation-waiting');
        $resToken = new Res();

        $userCreatedId = $this->userModel->createUser($this->user);

        if ($userCreatedId > -1) {
            if ($this->userModel->getUserById($userCreatedId)->getId() > -1) {
                $this->user = $this->userModel->getUserById($userCreatedId);
                $resToken = $this->tokenController->createUserToken($this->user->getId(), 'user-validation');
//                $this->dump($resToken);
            }

            if (!$resToken->isErr()) {
                // TODO : Create mail templates with twig

                // Build mail content
                $token = $resToken->getResult()['token'];
                $mailTo = $this->user->getEmail();
                $mailToName = $this->user->getPseudo();
                $mailSubject = 'Activation de votre compte';
                $mailContent = 'Bonjour ' . $this->user->getPseudo() . ',<br><br>';
                $mailContent .= 'Pour activer votre compte, veuillez cliquer sur le lien ci-dessous :<br />';
                $mailContent .= '<a href="http://ocp5blog/user/activation/' . $token . '">Activer mon compte</a><br /><br />';
                $mailContent .= 'Cordialement,<br />';
                $mailContent .= 'L\'équipe de p5blog';

                // Send mail
                $tokenValidateEmail = new MailController();
                $tokenValidateEmail->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);
            } else {
                $this->res->ko('reg-create-user', 'Erreur lors de la création du token de validation', null);
            }

        } else {
            $this->res->ko('reg-create-user', 'Erreur lors de la création de l\'utilisateur', null);
        }
        return $this->user;
    }


    /**
     * Updates a user providing a user object
     * @param User $user
     * @return Res
     */
    public function updateUser(User $user): Res
    {
        try {
            $result = $this->userModel->updateUser($user);
            if ($result === 0) {
                $this->res->ok('user-profile', 'user-profile-ok-no-change', null);
            } elseif ($result === 1) {
                $this->res->ok('user-profile', 'user-profile-ok-updated', null);
            } else {
                $this->res->ko('user-profile', 'user-profile-ko-error');
            }
        } catch (Exception $e) {
            $this->dump($e);
            $this->res->ko('user-profile', 'user-profile-ko-error');
        }
        return $this->res;
    }


    /**
     * @param UserOwner $userOwner
     * @return Res
     */
    public function updateUserOwner(UserOwner $userOwner): Res
    {
        $this->userOwnerModel = new UserOwnerModel();
        $result = $this->userOwnerModel->updateUserOwner($userOwner);

        if ($result === 0) {
            $this->res->ok('owner-profile', 'owner-profile-ok-no-change', null);
        } elseif ($result === 1) {
            $this->res->ok('owner-profile', 'owner-profile-ok-updated', null);
        } else {
            $this->res->ko('owner-profile', 'owner-profile-ko-error');
        }
        return $this->res;
    }

    public function getOwnerInfo()
    {
        $this->userOwnerModel = new UserOwnerModel();
        $maxId = $this->userOwnerModel->getLastOwnerId();
        return $this->userOwnerModel->getUserOwnerById($maxId);
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
