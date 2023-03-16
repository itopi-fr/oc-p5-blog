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

    public function login(string $email, string $password)
    {
        // hash
        $password = $this->hashPassword($password);
//        $this->dump($password);

        if ($this->userModel->userExistsByEmailPassword($email, $password)) {
            $this->user = $this->userModel->getUserByEmail($email);

            // start session
            $_SESSION['userid'] = $this->user->getId();

            $this->res->ok('connect','Connexion réussie', null);
        }
        else {
            $this->res->ko('connect','Aucune correspondance email/pass', null);
        }
        return $this->res;
    }

    public function logout()
    {
        // destroy session
        session_destroy();
        $this->res->ok('disconnect','Déconnexion réussie', null);
        return $this->res;
    }


}