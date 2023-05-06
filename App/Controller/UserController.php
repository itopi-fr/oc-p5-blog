<?php

namespace App\Controller;

use App\Controller\Form\FormUserLog;
use App\Controller\Form\FormUserChangePass;
use App\Controller\Form\FormUserProfile;
use App\Controller\Form\FormUserResetPass;
use App\Entity\Res;
use App\Entity\Token;
use App\Entity\UserOwner;
use App\Model\UserOwnerModel;
use App\Entity\User;
use App\Model\UserModel;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserController - User functions.
 */
class UserController extends MainController
{
    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var UserModel
     */
    protected UserModel $userModel;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var UserOwner
     */
    protected UserOwner $userOwner;

    /**
     * @var TokenController
     */
    protected TokenController $tokenController;

    /**
     * @var UserOwnerModel
     */
    protected UserOwnerModel $userOwnerModel;

    /**
     * @var FileController
     */
    protected FileController $fileController;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->tokenController = new TokenController();
        $this->userModel = new UserModel();
        $this->fileController = new FileController();
    }


    /**
     * Used as a sub-router for user actions
     *
     * @param $userAction - User action to perform.
     * @param $userActionData - Data to use for the action.
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index($userAction, $userActionData = null): void
    {
        /*
         * TODO: clean all this mess
         *  Distribute actions to separate methods : connect(), disconnect(), register(), etc.
        */

        // --------------------------------------------------------------------------------------------- session / User.
        $sessUserId = $this->sGlob->getSes('usrid');
        $userId = null;
        if (empty($sessUserId) === false) {
            $userId = $sessUserId;
        }

        $this->sGlob->setSes('userobj', null);
        if ($userId !== null) {
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
            // Form Reset Change sent : treat form.
            if (isset($userActionData) === true && empty($this->sGlob->getPost('submit-reset-pass-change')) === false) {
                $this->dump($userActionData);
                $this->twigData['result'] = (new FormUserResetPass())->treatFormPassChange($userActionData);
            } else {
                // Display Form Change Password.
                $this->twigData['display_form_reset_change'] = 'display';
            }
            // Display page.
            $this->twig->display("pages/page_bo_reset_pass.twig", $this->twigData);
            return;
        }

        // --------------------------------------------------------------------------------------------- user/connexion.
        if ($userAction === 'connexion') {
            if (empty($this->sGlob->getPost('submit-connect')) === false) {
                $resUserLogin = $this->userLogin();
                $this->twigData['result'] = $resUserLogin;

                if ($resUserLogin->isErr() === true) {
                    $this->redirectTo('/user/connexion', 5);
                    // Display page.
                    $this->twig->display("pages/page_bo_user.twig", $this->twigData);
                    return;
                }

                // Login success - redirect to the right page.
                if ($resUserLogin->getResult()['user-login']->getRole() == 'owner') {
                    $this->redirectTo('/owner', 0);
                } else {
                    $this->redirectTo('/user/profile', 0);
                }
            }
        }

        // ------------------------------------------------------------------------------------------------ user/profil.
        if ($userId !== null) {
            $this->user = $this->userModel->getUserById($userId);

            // Owner Profile.
            if ($this->user->getRole() == 'owner') {
                $this->userOwner = (new UserOwnerModel())->getUserOwnerById($this->user->getUserId());

                if ($userAction == 'profil' && empty($this->sGlob->getPost('submit-owner-profile')) === false) {
                    $this->twigData['result'] = (new FormUserProfile())->treatFormUserOwner($this->userOwner);
                    $this->sGlob->setSes('ownerinfo', (new OwnerInfoController())->getOwnerInfo());

                    // Refresh page if no error.
                    if ($this->twigData['result']->isErr() === false) {
                        $this->refresh(3);
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
                $this->redirectTo('/', 0);
            }

            // User.
            $this->twigData['user'] = $this->user;
        }

        // Display page.
        $this->twig->display("pages/page_bo_user.twig", $this->twigData);
    }


    /**
     * Login a user
     *
     * @return Res
     */
    public function userLogin(): Res
    {
        // Check that a user exists with this email and get that user if exists.
        $resUserByEmail = $this->getUserByEmail($this->sGlob->getPost('email'));
        if ($resUserByEmail->isErr()) {
            return $this->res->ko('user-login', 'user-login-ko-no-user-pass-match');
        }
        $user = $resUserByEmail->getResult()['user-by-email'];

        // Check if user is banned.
        if ($user->getRole() == 'user-banned') {
            return $this->res->ko('user-login', 'user-login-ko-account-banned');
        }
        // Check if user is waiting for validation.
        if ($user->getRole() == 'user-validation') {
            return $this->res->ko('user-login', 'user-login-ko-account-token-not-validated');
        }

        // TODO: Add Captcha and track IP login attempts.

        // Start the login process.
        return (new FormUserLog())->login($this->sGlob->getPost('email'), $this->sGlob->getPost('pass'));
    }


    /**
     * Activate a user account. This method is called when a user click on the activation link in the email.
     * This email contains a link with a token : /user/activation/123456789
     * If the token is valid, not expired and the user exists, the user is activated and the token is deleted.
     *
     * @param string $tokenContent - Token key to activate the user.
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

        // Verify token.
        $resVerifyToken = $this->tokenController->verifyToken($token->getContent(), $this->user->getEmail());
        if ($resVerifyToken->getMsg()['verify-token'] !== 'verify-token-ok') {
            $this->res->ko('user-activate', 'user-activate-ko-verify-token');
            return $this->res;
        }

        // Update User Role.
        $this->user->setRole('user');

        // Update User.
        if ($this->userModel->updateUser($this->user) === null) {
            $this->res->ko('user-activate', 'user-activate-ko-failed');
            return $this->res;
        }

        // Delete Token.
        $this->tokenController->deleteTokenById($token->getTokenId());
        $this->res->ok('user-activate', 'user-activate-account-activated');
        $this->redirectTo('/user/connexion', 5);
        return $this->res;
    }


    /**
     * Get all users.
     *
     * @return Res
     */
    public function getAllUsers(): Res
    {
        $resAllUsers = $this->userModel->getAllUsers();

        if (empty($resAllUsers)) {
            $this->res->ko('all-users', 'all-users-ko');
            return $this->res;
        }

        // Hydrate standard objects to proper User objects.
        $allUsers = [];
        foreach ($resAllUsers as $userObj) {
            $allUsers[] = $this->hydrateUser($userObj);
        }

        $this->res->ok('all-users', 'all-users-ok', $allUsers);
        return $this->res;
    }


    /**
     * Get a user by its id.
     *
     * @param int $userId - the ID of the user to get.
     * @return User
     */
    public function getUserById(int $userId): User
    {
        return $this->userModel->getUserById($userId);
    }


    /**
     * Get a user by its email.
     *
     * @param string $email - the email of the user to get.
     * @return Res
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
     * Get a user by a token.
     *
     * @param int|string $tokenData - the token (id or key) to get the user from.
     * @return Res
     */
    public function getUserByToken(int|string $tokenData): Res
    {
        // Get Token.
        $token = new Token();
        $resToken = $this->tokenController->getToken($tokenData);
        if ($resToken->isErr() === true) {
            $this->res->ko('user-by-token', 'user-by-token-token-not-found');
            return $this->res;
        }
        $token = $resToken->getResult()['token'];

        // Get User.
        if ($this->userModel->userExistsById($token->getUserId()) === false) {
            $this->res->ko('user-by-token', 'user-by-token-assoc-user-not-found');
            return $this->res;
        }

        // If everything is ok, return the user.
        $this->res->ok('user-by-token', 'user-by-token-ok', $this->userModel->getUserById($token->getUserId()));
        return $this->res;
    }


    /**
     * Create a user providing a pseudo, an email and a password.
     *
     * @param string $pseudo - the pseudo of the user to create.
     * @param string $email - the email of the user to create.
     * @param string $password - the password of the user to create.
     * @return Res
     */
    public function regCreateUser(string $pseudo, string $email, string $password): Res
    {
        // TODO: make the default avatar work.
        $this->res = new Res();
        $this->user = new User();
        $this->user->setPseudo($pseudo);
        $this->user->setEmail($email);
        $this->user->setPass($password);
        $this->user->setAvatarId(1);
        $this->user->setRole('user-validation');
        $resToken = new Res();

        // Create user.
        $userCreatedId = $this->userModel->createUser($this->user);
        if ($userCreatedId === null) {
            $this->res->ko('reg-create-user', 'reg-create-user-ko');
            return $this->res;
        }

        // Get user.
        $getUser = $this->userModel->getUserById($userCreatedId);
        if ($getUser === null) {
            $this->res->ko('reg-create-user', 'reg-create-user-ko');
            return $this->res;
        }
        $this->user = $getUser;
        $resToken = $this->tokenController->createUserToken($this->user->getUserId(), 'user-validation');

        if ($resToken->isErr() === true) {
            $this->res->ko('reg-create-user', 'Erreur lors de la création du token de validation');
            return $this->res;
        }

        // TODO : Create mail templates with twig.

        // Build mail content.
        $token = $resToken->getResult()['token'];
        $mailTo = $this->user->getEmail();
        $mailToName = $this->user->getPseudo();
        $mailSubject = 'Activation de votre compte';
        $mailContent = 'Bonjour ' . $this->user->getPseudo() . ',<br><br>';
        $mailContent .= 'Pour activer votre compte, veuillez cliquer sur le lien ci-dessous :<br />';
        $mailContent .= '<a href="http://ocp5blog/user/activation/' . $token . '">Activer mon compte</a><br /><br />';
        $mailContent .= 'Cordialement,<br />';
        $mailContent .= 'L\'équipe de p5blog';

        // TODO : Check result of sendMail before returning ok.

        // Send mail.
        $tokenValidateEmail = new MailController();
        $tokenValidateEmail->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);

        $this->res->ok('reg-create-user', 'reg-create-user-success', $this->user);

        return $this->res;
    }


    /**
     * Updates a user providing a user object.
     *
     * @param User $user - the user to update.
     * @return Res
     */
    public function updateUser(User $user): Res
    {
        try {
            // Remove old avatar file if a new one is sent.
            $dbAvatarId = $this->getUserById($user->getUserId())->getAvatarId();
            if ($dbAvatarId !== $user->getAvatarId() && $dbAvatarId !== null) {
                $this->fileController->deleteFileById($dbAvatarId);
            }

            // Update the user.
            $result = $this->userModel->updateUser($user);
            if ($result === 0) {
                $this->res->ok('user-profile', 'user-profile-ok-no-change');
            } elseif ($result === 1) {
                $this->res->ok('user-profile', 'user-profile-ok-updated');
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
     * Updates owner info providing a userOwner object.
     *
     * @param UserOwner $userOwner - the userOwner to update.
     * @return Res
     */
    public function updateUserOwner(UserOwner $userOwner): Res
    {
        $this->userOwnerModel = new UserOwnerModel();
        $dbUserOwner = $this->userOwnerModel->getUserOwnerById($userOwner->getUserId());

        // Remove old photo file if a new one is sent.
        if ($dbUserOwner->getPhotoFileId() !== $userOwner->getPhotoFileId() && $dbUserOwner->getPhotoFileId() !== null) {
            $this->fileController->deleteFileById($dbUserOwner->getPhotoFileId());
        }

        // Remove old cv file if a new one is sent.
        if ($dbUserOwner->getCvFileId() !== $userOwner->getCvFileId() && $dbUserOwner->getCvFileId() !== null) {
            $this->fileController->deleteFileById($dbUserOwner->getCvFileId());
        }

        // Update the user.
        $result = $this->userOwnerModel->updateUserOwner($userOwner);

        if ($result === 0) {
            $this->res->ok('owner-profile', 'owner-profile-ok-no-change');
        } elseif ($result === 1) {
            $this->res->ok('owner-profile', 'owner-profile-ok-updated');
        } else {
            $this->res->ko('owner-profile', 'owner-profile-ko-error');
        }
        return $this->res;
    }


    /**
     * Mute a user by setting his role to 'user-muted'.
     *
     * @param int $userId - the ID of the user to mute.
     * @return Res
     */
    public function muteUser(int $userId): Res
    {
        $user = $this->userModel->getUserById($userId);
        if ($user === null) {
            $this->res->ko('mute-user', 'mute-user-ko-user-not-found');
            return $this->res;
        }
        $user->setRole('user-muted');
        $result = $this->userModel->updateUser($user);
        if ($result === 0) {
            $this->res->ok('mute-user', 'mute-user-ok-no-change');
        } elseif ($result === 1) {
            $this->res->ok('mute-user', 'mute-user-ok-updated');
        } else {
            $this->res->ko('mute-user', 'mute-user-ko-error');
        }
        return $this->res;
    }


    /**
     * Activate a user by setting his role to 'user'.
     *
     * @param int $userId - the ID of the user to activate.
     * @return Res
     */
    public function activateUser(int $userId): Res
    {
        $user = $this->userModel->getUserById($userId);
        if ($user === null) {
            $this->res->ko('activate-user', 'activate-user-ko-user-not-found');
            return $this->res;
        }
        $user->setRole('user');
        $result = $this->userModel->updateUser($user);
        if ($result === 0) {
            $this->res->ok('activate-user', 'activate-user-ok-no-change');
        } elseif ($result === 1) {
            $this->res->ok('activate-user', 'activate-user-ok-updated');
        } else {
            $this->res->ko('activate-user', 'activate-user-ko-error');
        }
        return $this->res;
    }


    /**
     * Bans a user by setting his role to 'user-banned'.
     *
     * @param int $userId - the ID of the user to ban.
     * @return Res
     */
    public function banUser(int $userId): Res
    {
        $user = $this->userModel->getUserById($userId);
        if ($user === null) {
            $this->res->ko('ban-user', 'ban-user-ko-user-not-found');
            return $this->res;
        }
        $user->setRole('user-banned');
        $result = $this->userModel->updateUser($user);
        if ($result === 0) {
            $this->res->ok('ban-user', 'ban-user-ok-no-change');
        } elseif ($result === 1) {
            $this->res->ok('ban-user', 'ban-user-ok-updated');
        } else {
            $this->res->ko('ban-user', 'ban-user-ko-error');
        }
        return $this->res;
    }


    /**
     * Email a user.
     *
     * @param int $toUserId - the ID of the user to send the email to.
     * @param string $subject - the subject of the email.
     * @param string $content - the content of the email.
     * @return Res
     */
    public function sendEmailToUser(int $toUserId, string $subject, string $content): Res
    {
        $userTo = $this->userModel->getUserById($toUserId);
        if ($userTo === null) {
            $this->res->ko('send-email-to-user', 'send-email-to-user-ko-user-not-found');
            return $this->res;
        }
        $mailTo = $userTo->getEmail();
        $mailToName = $userTo->getPseudo();
        $mailSubject = $subject;
        $mailContent = $content;

        // Send mail.
        $mailController = new MailController();
        $resSendMail = $mailController->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);

        if ($resSendMail->isErr() === true) {
            $this->res->ko('send-email-to-user', 'send-email-to-user-ko-error');
            return $this->res;
        }

        $this->res->ok('send-email-to-user', 'send-email-to-user-ok', $userTo);
        return $this->res;
    }


    /**
     * Email the owner of the blog.
     *
     * @param string $fromUserEmail - the email of the user sending the mail.
     * @param string $firstname - the firstname of the user sending the mail.
     * @param string $lastname - the lastname of the user sending the mail.
     * @param string $subject - the subject of the mail.
     * @param string $content - the content of the mail.
     * @return Res
     */
    public function sendEmailToOwner(
        string $fromUserEmail,
        string $firstname,
        string $lastname,
        string $subject,
        string $content
    ): Res {
        // User sending the mail.
        $userFrom = $this->userModel->getUserByEmail($fromUserEmail);
        if ($userFrom === null) {
            $this->res->ko('form-contact-owner', 'form-contact-owner-ko-user-not-found');
            return $this->res;
        }

        // MailTo : Owner of the blog.
        $mailToOwnerMail = $this->sGlob->getEnv('BLOG_CONTACT_EMAIL');

        // Build mail.
        $mailTo = $mailToOwnerMail;
        $mailToName = "Admin " . $this->sGlob->getEnv('BLOG_NAME');
        $mailSubject = "[" . $this->sGlob->getEnv('BLOG_NAME') . " - Contact] " . $subject;

        // Build mail link.
        $replyHref = '<a href="';
        $replyHref .= $this->sGlob->getEnv('BLOG_URL') . '/owner/user-sendmail/' . $userFrom->getUserId();
        $replyHref .= '">Répondre à ' . $userFrom->getPseudo() . '</a><br /><br />';

        // Build mail content.
        $mailContent = 'Message de ' . $userFrom->getPseudo() . ' (' . $userFrom->getEmail() . ').<br />';
        $mailContent .= 'Nom : ' . $firstname . ' ' .  $lastname . '<br /><br />';
        $mailContent .= 'Vous pouvez répondre à cet utilisateur en cliquant sur ce lien :<br />' . $replyHref;
        $mailContent .= "Message :<br />";
        $mailContent .= $content;

        // Send mail.
        $mailController = new MailController();
        $resSendMail = $mailController->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);

        if ($resSendMail->isErr() === true) {
            $this->res->ko('form-contact-owner', $resSendMail->getMsg()['send-email']);
            return $this->res;
        }

        $this->res->ok('form-contact-owner', 'form-contact-owner-ok', $userFrom);
        return $this->res;
    }


    /**
     * Hydrates a proper User object with data from the database.
     *
     * @param object $userObj - the object to hydrate.
     * @return User
     */
    public function hydrateUser(object $userObj): User
    {
        $user = new User();
        $user->setUserId($userObj->user_id);
        $user->setPseudo($userObj->pseudo);
        $user->setEmail($userObj->email);
        $user->setPass($userObj->pass);
        $user->setAvatarId($userObj->avatar_id);
        $user->setRole($userObj->role);

        // Get Avatar File.
        if (empty($userObj->avatar_id) === false) {
            if ($this->fileController->fileExistsById($userObj->avatar_id) === true) {
                $user->setAvatarFile($this->fileController->getFileById($userObj->avatar_id));
            }
        }
        return $user;
    }


}
