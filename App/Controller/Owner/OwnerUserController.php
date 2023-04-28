<?php

namespace App\Controller\Owner;

use App\Controller\Form\FormSendMailToUser;
use App\Controller\UserController;
use App\Entity\Res;

/**
 * Class OwnerUserController - Manage the users in the BO (owner).
 */
class OwnerUserController extends OwnerController
{
    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var UserController
     */
    protected UserController $userController;
    private FormSendMailToUser $formSendMailToUser;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->userController = new UserController();
        $this->formSendMailToUser = new FormSendMailToUser();
    }


    /**
     * Manage the users
     * @return void
     */
    public function manageUsers(): void
    {
        $this->twigData['title'] = 'Administration des utilisateurs';

        // TODO: display a human readable role
        // Get the users
        $this->twigData['users'] = $this->userController->getAllUsers()->getResult()['all-users'];
        $this->twig->display("pages/owner/page_bo_users_manage.twig", $this->twigData);
    }


    /**
     * Mute a user by its id by setting its role to 'user-muted'
     * @param string $userId
     * @return void
     */
    public function muteUser(string $userId): void
    {
        $this->userController->muteUser($userId);
        $this->manageUsers();
    }


    /**
     * Unmute a user by its id by setting its role to 'user'
     * @param string $userId
     * @return void
     */
    public function activateUser(string $userId): void
    {
        $this->userController->activateUser($userId);
        $this->manageUsers();
    }


    /**
     * Ban a user by its id by setting its role to 'user-banned'
     * @param string $userId
     * @return void
     */
    public function banUser(string $userId): void
    {
        $this->userController->banUser((int) $userId);
        $this->manageUsers();
    }

    /**
     * Displays the form to email a user or treats it if sent
     * @param string $userId
     * @return void
     */
    public function sendmailToUser(string $strUserId): void
    {
        $this->twigData['title'] = 'Envoyer un email à un utilisateur';
        $this->twigData['mailtoUserId'] = (int) $strUserId;

        // Email form sent => Treat it.
        if (empty($this->sGlob->getPost("submit-user-sendmail")) === false) {
            if (empty($this->sGlob->getPost("send-mail-user-id")) === true) {
                $this->twigData['result'] = $this->res->ko('send-mail-to-user', 'send-mail-to-user-ko-user-id-empty');
            }
            $this->twigData['result'] = $this->formSendMailToUser->treatForm();
        }


//        if ($this->sGlob->getPost('submit')) {
//            $this->userController->sendmailToUser($userId);
//            $this->twigData['message'] = 'Votre email a bien été envoyé.';
//        }
        $this->twig->display("pages/owner/page_bo_user_sendmail.twig", $this->twigData);
    }


}
