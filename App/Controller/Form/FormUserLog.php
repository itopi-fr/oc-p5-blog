<?php

namespace App\Controller\Form;

use App\Controller\MainController;
use App\Controller\UserController;
use App\Entity\Res;
use App\Entity\User;
use App\Model\UserModel;

/**
 * Class FormUserLog - Manage the user login, logout and register forms.
 */
class FormUserLog extends FormController
{
    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var UserController
     */
    protected UserController $userController;

    /**
     * @var UserModel
     */
    protected UserModel $userModel;

    /**
     * @var MainController
     */
    protected MainController $mainCtr;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->userController = new UserController();
        $this->userModel = new UserModel();
        $this->mainCtr = new MainController();
    }


    /**
     * This method is used to create a new user.
     * It checks if entered passwords match, if the pseudo and email are not already used.
     *
     * @param string $pseudo - The user pseudo.
     * @param string $email - The user email.
     * @param string $password - The user password.
     * @param string $password2 - The user password confirmation.
     * @return Res
     */
    public function register(string $pseudo, string $email, string $password, string $password2): Res
    {
        //Checks.
        if ($password !== $password2) {
            $this->res->ko('register', 'pass-not-match');
            return $this->res;
        }

        if ($this->userModel->userExistsByPseudo($pseudo) === true) {
            $this->res->ko('register', 'Pseudo déjà utilisé');
            return $this->res;
        }

        if ($this->userModel->userExistsByEmail($email) === true) {
            $this->res->ko('register', 'Email déjà utilisé');
            return $this->res;
        }

        if ($this->checkPasswordFormat($password) === false) {
            $this->res->ko('register', 'pass-format');
            return $this->res;
        }

        // TODO : Check email and pseudo format.

        // Hash.
        $password = $this->hashPassword($password);

        // If ok, create user.
        $createdUser = $this->userController->regCreateUser($pseudo, $email, $password)->getResult()['reg-create-user'];

        if ($createdUser === null) {
            $this->res->ko('register', 'register-fail');
            return $this->res;
        }

        $this->res->ok('register', 'register-success-wait-mail-confirm');
        return $this->res;
    }


    /**
     * Log in a user.
     *
     * @param string $email - The user email.
     * @param string $password - The user password.
     * @return Res
     */
    public function login(string $email, string $password): Res
    {
        // Hash.
        $password = $this->hashPassword($password);

        if ($this->userModel->userExistsByEmailPassword($email, $password)) {
            $this->user = $this->userModel->getUserByEmail($email);

            // Save to session.
            $this->sGlob->setSes('usrid', $this->user->getUserId());
            $this->sGlob->setSes('userobj', $this->user);

            $this->res->ok('user-login', 'user-login-ok-success', $this->user);
        } else {
            $this->res->ko('user-login', 'user-login-ko-no-user-pass-match');
        }
        return $this->res;
    }


    /**
     * Logs out a user by destroying the session.
     *
     * @return Res
     */
    public function logout(): Res
    {
        // Destroy session.
        session_destroy();
        $this->res->ok('disconnect', 'Déconnexion réussie');
        return $this->res;
    }
}
