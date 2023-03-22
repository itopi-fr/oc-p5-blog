<?php

namespace App\Controller\Form;

use App\Controller\MainController;
use App\Controller\UserController;
use App\Entity\Res;
use App\Entity\User;
use App\Model\UserModel;

class FormLogInOutReg extends FormController
{
    private Res $res;

    private User $user;
    private UserController $userController;
    private UserModel $userModel;
    private MainController $mc;


    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->userController = new UserController();
        $this->userModel = new UserModel();
        $this->mc = new MainController();
    }

    public function register(string $pseudo, string $email, string $password, string $password2)
    {
        // hash
        $password = $this->hashPassword($password);
        $password2 = $this->hashPassword($password2);

        //Checks
        if ($password !== $password2) {
            $this->res->ko('register', $this->res->showMsg('pass-not-match'), null);
        }

        if ($this->userModel->userExistsByEmail($email)) {
             $this->res->ko('register', 'Email déjà utilisé', null);
        }

        if (!$this->checkPasswordFormat($password)) {
            $this->res->ko('register', $this->res->showMsg('pass-format'), null);
        }

        // TODO : checks de format de l'email, du pseudo

        // If ok
        if ($this->userController->regCreateUser($pseudo, $email, $password)->getId() > -1) {
            $this->res->ok('register', $this->res->showMsg('register-success-wait-mail-confirm'), null);
        } else {
            $this->res->ko('register', $this->res->showMsg('register-fail'), null);
        }

        return $this->res;
    }

    public function login(string $email, string $password)
    {
        // hash
        $password = $this->hashPassword($password);
//        $this->dump($password);

        if ($this->userModel->userExistsByEmailPassword($email, $password)) {
            $this->user = $this->userModel->getUserByEmail($email);

            // start session
            $_SESSION['userid'] = $this->user->getId();

            $this->res->ok('connect', 'Connexion réussie', null);
        } else {
            $this->res->ko('connect', 'Aucune correspondance email/pass', null);
        }
        return $this->res;
    }

    public function logout()
    {
        // destroy session
        session_destroy();
        $this->res->ok('disconnect', 'Déconnexion réussie', null);
        return $this->res;
    }


}