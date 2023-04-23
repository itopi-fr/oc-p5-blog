<?php

namespace App\Controller;

use App\Controller\Form\FormUserLog;
use App\Controller\Form\FormUserChangePass;
use App\Controller\Form\FormUserProfile;
use App\Controller\Form\FormUserResetPass;
use App\Entity\Res;
use App\Entity\Token;
use App\Entity\UserOwner;
use App\Model\MailModel;
use App\Model\UserOwnerModel;
use App\Entity\User;
use App\Model\UserModel;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends MainController
{
    protected Res $res;

    protected UserModel $userModel;

    protected User $user;

    protected UserOwner $userOwner;

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


    /**
     * Used as a sub-router for user actions
     * @param $userAction
     * @param $userActionData
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index($userAction, $userActionData = null)
    {
        /*
         * TODO: clean all this mess
         *  Distribute actions to separate methods : connect(), disconnect(), register(), etc.
        */

        // --------------------------------------------------------------------------------------------- session / User.
        $sessUserId = $this->sGlob->getSes('userid');
        $userId = null;
        if (empty($sessUserId) === false) {
            $userId = $sessUserId;
        }
//        empty($sessUserId) === false ? $userId = $sessUserId : $userId = null;

        $this->sGlob->setSes('userobj', null);
        if ($userId === null) {
            $this->sGlob->setSes('userobj', $this->userModel->getUserById($sessUserId));
        }

        // ------------------------------------------------------------------------------------------- user/inscription.
        if ($userAction === 'inscription') {
            // Form Register.
            if (empty($this->sGlob->getPost('submit-register')) === false) {
                $this->twigData['result'] = (new FormUserLog())->register(
                    $this->sGlob->getPost('pseudo'),
                    $this->sGlob->getPost('email'),
                    $this->sGlob->getPost('pass'),
                    $this->sGlob->getPost('pass-confirm')
                );
            }

            // Display page.
            $this->twig->display("pages/page_bo_register.twig", $this->twigData);
            return;
        }

        // ---------------------------------------------------------------------------------------- user/activation/123.
        if ($userAction === 'activation') {
            if (isset($userActionData) === true) {
                $this->twigData['result'] = $this->userActivate($userActionData);
            }
            // Display page.
            $this->twig->display("pages/page_bo_activate.twig", $this->twigData);
            return;
        }

        // ---------------------------------------------------------------------------------------- user/reset-pass-ask.
        if ($userAction === 'reset-pass-ask') {
            if (empty($this->sGlob->getPost('submit-reset-pass-ask')) === false) {
                // Form Reset sent : treat form.
                $this->twigData['result'] = (new FormUserResetPass())->treatFormPassAsk($this->sGlob->getPost('email'));
            } else {
                // Form Reset not sent : display form.
                $this->twigData['display_form_reset_ask'] = 'display';
            }
            // Display page.
            $this->twig->display("pages/page_bo_reset_pass.twig", $this->twigData);
            return;
        }

        // ------------------------------------------------------------------------------------- user/reset-pass-change.
        if ($userAction === 'reset-pass-change') {
            // Form Reset Change sent : treat form
            if (isset($userActionData) === true && empty($this->sGlob->getPost('submit-reset-pass-change')) === false) {
                $this->twigData['result'] = (new FormUserResetPass())->treatFormPassChange(
                    $userActionData
                );
            } else {
                // Display Form Change Password
                $this->twigData['display_form_reset_change'] = 'display';
            }
            // Display page
            $this->twig->display("pages/page_bo_reset_pass.twig", $this->twigData);
            return;
        }

        // --------------------------------------------------------------------------------------------- user/connexion.
        if ($userAction === 'connexion' && empty($this->sGlob->getPost('submit-connect')) === false) {
            $this->userLogin();
        }

        // ------------------------------------------------------------------------------------------------ user/profil.
        if ($userId !== null) {
            $this->user = $this->userModel->getUserById($userId);

            // Owner Profile.
            if ($this->user->getRole() == 'owner') {
                $this->userOwner = (new UserOwnerModel())->getUserOwnerById($this->user->getId());

                if ($userAction == 'profil' && empty($this->sGlob->getPost('submit-owner-profile')) === false) {
                    $this->twigData['result'] = (new FormUserProfile())->treatFormUserOwner($this->userOwner);
                    $this->sGlob->setSes('ownerinfo', (new OwnerInfoController())->getOwnerInfo());

                    // Refresh page if no error.
                    if ($this->twigData['result']->isErr() === false) {
                        $this->refresh();
                    }

                }
                $this->twigData['owner'] = $this->userOwner;
            }

            // User Profile.
            if ($userAction == 'profil' && empty($this->sGlob->getPost('submit-user-profile')) === false) {
                $this->twigData['result'] = (new FormUserProfile())->treatFormUser($this->user);
            }

            // Form User Change Password.
            if ($userAction == 'profil' && empty($this->sGlob->getPost('submit-user-pass')) === false) {
                $this->twigData['result'] = (new FormUserChangePass())->treatFormChangePass(
                    $this->user,
                    $this->sGlob->getPost('pass-old'),
                    $this->sGlob->getPost('pass-new-a'),
                    $this->sGlob->getPost('pass-new-b'),
                    false
                );
            }

            // Form Logout.
            if ($userAction === 'deconnexion') {
                $this->twigData['result'] = (new FormUserLog())->logout();
                $this->refresh(0);
            }

            // User.
            $this->twigData['user'] = $this->user;
        }

        // Display page.
        $this->twig->display("pages/page_bo_user.twig", $this->twigData);
    }


    /**
     * Login a user
     * @return void
     */
    public function userLogin(): void
    {
        $resUserByEmail = $this->getUserByEmail($this->sGlob->getPost('email'));
        if ($resUserByEmail->isErr()) {
            $this->res->ko('user-login', 'user-login-ko-no-user-pass-match');
            $this->twigData['result'] = $this->res;
            return;
        }

        $user = $resUserByEmail->getResult()['user-by-email'];

        if ($user->getRole() == 'user-validation') {
            $this->res->ko('user-login', 'user-login-ko-account-token-not-validated');
            $this->twigData['result'] = $this->res;
            return;
        }
        $this->twigData['result'] = (new FormUserLog())->login(
            $this->sGlob->getPost('email'),
            $this->sGlob->getPost('pass')
        );
        $this->refresh(2);
    }


    /**
     * Activate a user account. This method is called when a user click on the activation link in the email.
     * This email contains a link with a token : /user/activation/123456789
     * If the token is valid, not expired and the user exists, the user is activated and the token is deleted.
     * @param string $tokenContent
     * @return Res
     */
    public function userActivate(string $tokenContent): Res
    {
        // Get token.
        $token = new Token();
        $resToken = $this->tokenController->getToken($tokenContent);
        if ($resToken->isErr() === true) {
            $this->res->ko('user-activate', 'user-activate-ko-token-not-found');
            return $this->res;
        }
        $token = $resToken->getResult()['token'];

        // Get User by Token Content.
        $resUserByToken = $this->getUserByToken($token->getContent());
        if ($resUserByToken->isErr() === true) {
            $this->res->ko('user-activate', 'user-activate-ko-user-by-token');
            return $this->res;
        }
        $this->user = $resUserByToken->getResult()['user-by-token'];

        // Verify token
        $resVerifyToken = $this->tokenController->verifyToken($token->getContent(), $this->user->getEmail());
        if ($resVerifyToken->getMsg()['verify-token'] !== 'verify-token-ok') {
            $this->res->ko('user-activate', 'user-activate-ko-verify-token');
            return $this->res;
        }

        // Update User Role
        $this->user->setRole('user');

        // Update User
        if ($this->userModel->updateUser($this->user) === null) {
            $this->res->ko('user-activate', 'user-activate-ko-failed');
            return $this->res;
        }

        // Delete Token
        $this->tokenController->deleteTokenById($token->getId());
        $this->res->ok('user-activate', 'user-activate-account-activated', null);
        $this->redirectTo('/user/connexion', 5);
        return $this->res;
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
    public function getUserByEmail(string $email): Res
    {
        if ($this->userModel->userExistsByEmail($email) === false) {
            $this->res->ko('user-by-email', 'user-by-email-not-found');
            return $this->res;
        }

        return $this->res->ok('user-by-email', 'user-by-email-found', $this->userModel->getUserByEmail($email));
    }


    /**
     * Get a user by a token
     * @param int|string $tokenData
     * @return Res
     */
    public function getUserByToken(int|string $tokenData): Res
    {
        // Get Token
        $token = new Token();
        $resToken = $this->tokenController->getToken($tokenData);
        if ($resToken->isErr() === true) {
            $this->res->ko('user-by-token', 'user-by-token-token-not-found');
            return $this->res;
        }
        $token = $resToken->getResult()['token'];

        // Get User
        if ($this->userModel->userExistsById($token->getUserId()) === false) {
            $this->res->ko('user-by-token', 'user-by-token-assoc-user-not-found');
            return $this->res;
        }

        // If everything is ok, return the user
        $this->res->ok('user-by-token', 'user-by-token-ok', $this->userModel->getUserById($token->getUserId()));
        return $this->res;
    }


    /**
     * Create a user providing a pseudo, an email and a password
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @return Res
     */
    public function regCreateUser(string $pseudo, string $email, string $password): Res
    {
        $this->res = new Res();
        $this->user = new User();
        $this->user->setPseudo($pseudo);
        $this->user->setEmail($email);
        $this->user->setPass($password);
        $this->user->setAvatarId(1); // default avatar
        $this->user->setRole('user-validation');
        $resToken = new Res();

        // Create user
        $userCreatedId = $this->userModel->createUser($this->user);
        if (is_null($userCreatedId) === true) {
            $this->res->ko('reg-create-user', 'reg-create-user-ko');
            return $this->res;
        }

        // Get user
        $getUser = $this->userModel->getUserById($userCreatedId);
        if (is_null($getUser) === true) {
            $this->res->ko('reg-create-user', 'reg-create-user-ko');
            return $this->res;
        }
        $this->user = $getUser;
        $resToken = $this->tokenController->createUserToken($this->user->getId(), 'user-validation');

        if ($resToken->isErr() === true) {
            $this->res->ko('reg-create-user', 'Erreur lors de la création du token de validation');
            return $this->res;
        }

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

        // TODO : Check result of sendMail before returning ok
        // Send mail
        $tokenValidateEmail = new MailController();
        $tokenValidateEmail->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);

        $this->res->ok('reg-create-user', 'reg-create-user-success', $this->user);

        return $this->res;
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
