<?php

namespace App\Controller\Form;

use App\Entity\Res;
use App\Model\UserModel;
use App\Controller\UserController;

/**
 * Treats the form to mail a user.
 */
class FormSendMailToUser extends FormController
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
     * @var UserController
     */
    protected UserController $userController;

    /**
     * @var int
     */
    protected int $userId;

    /**
     * @var string
     */
    protected string $subject;

    /**
     * @var string
     */
    protected string $message;



    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->userModel = new UserModel();
        $this->userController = new UserController();
    }


    /**
     * Treat the form to send a mail to a user
     * @return Res
     */
    public function treatForm(): Res
    {
        if (empty($this->sGlob->getPost('send-mail-user-id')) === true) {
            return $this->res->ko('form-mailto-user', 'form-mailto-user-ko-user-id-empty');
        }
        $userId = (int) $this->sGlob->getPost('send-mail-user-id');

        // -------------------------------------------------------------------- Checks.
        // User exists
        $resCheckUserId = $this->userModel->userExistsById($userId);
        if ($resCheckUserId === false) {
            return $this->res->ko('form-mailto-user', 'form-mailto-user-ko-user-not-found');
        }

        // Mail Subject : checks.
        $resCheckSubject = $this->checkPostedText(
            'form-mailto-user',
            'mail-subject',
            3,
            64
        );
        if ($resCheckSubject->isErr() === true) {
            return $this->res->ko('form-mailto-user', $resCheckSubject->getMsg()['send-email-to-user']);
        }

        // Mail Message : checks.
        $resCheckContent = $this->checkPostedText(
            'form-mailto-user',
            'mail-content',
            3,
            1024
        );
        if ($resCheckContent->isErr() === true) {
            return $this->res->ko('form-mailto-user', $resCheckContent->getMsg()['send-email-to-user']);
        }

        // -------------------------------------------------------------------- Set the data.
        $this->userId = $userId;
        $this->subject = $this->sGlob->getPost('mail-subject');
        $this->message = $this->sGlob->getPost('mail-content');

        // -------------------------------------------------------------------- Send the email.
        $resSendMail = $this->userController->sendEmailToUser($userId, $this->subject, $this->message);
        if ($resSendMail->isErr() === true) {
            return $this->res->ko('form-mailto-user', $resSendMail->getMsg()['send-email-to-user']);
        }
        return $this->res->ok('form-mailto-user', $resSendMail->getMsg()['send-email-to-user']);
    }


}
