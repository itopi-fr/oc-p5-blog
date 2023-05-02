<?php

namespace App\Controller\Form;

use App\Controller\OwnerInfoController;
use App\Controller\UserController;
use App\Entity\Res;
use App\Model\UserModel;

/**
 * Treats the form to contact the owner of the blog by mail.
 */
class FormContactOwner extends FormController
{
    /**
     * @var Res
     */
    protected Res $res;
    protected UserModel $userModel;
    protected UserController $userController;


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
     * @return Res $res
     */
    public function treatForm(): Res
    {
        $fromUserId = $this->sGlob->getSes('usrid');

        // -------------------------------------------------------------------- Checks.
        // User exists
        $resCheckUserId = $this->userModel->userExistsById($fromUserId);
        if ($resCheckUserId === false) {
            return $this->res->ko('form-contact-owner', 'form-contact-owner-ko-user-from-not-found');
        }

        // Mail Subject : checks.
        $resCheckSubject = $this->checkPostedText(
            'form-contact-owner',
            'mail-subject',
            3,
            64
        );
        if ($resCheckSubject->isErr() === true) {
            return $this->res->ko('form-contact-owner', $resCheckSubject->getMsg()['form-contact-owner']);
        }

        // Mail Firstname : checks.
        $resCheckSubject = $this->checkPostedText(
            'form-contact-owner',
            'mail-firstname',
            3,
            64
        );
        if ($resCheckSubject->isErr() === true) {
            return $this->res->ko('form-contact-owner', $resCheckSubject->getMsg()['form-contact-owner']);
        }

        // Mail Lastname : checks.
        $resCheckSubject = $this->checkPostedText(
            'form-contact-owner',
            'mail-lastname',
            3,
            64
        );
        if ($resCheckSubject->isErr() === true) {
            return $this->res->ko('form-contact-owner', $resCheckSubject->getMsg()['form-contact-owner']);
        }

        // Mail Message : checks.
        $resCheckContent = $this->checkPostedText(
            'form-contact-owner',
            'mail-content',
            3,
            1024
        );
        if ($resCheckContent->isErr() === true) {
            return $this->res->ko('form-contact-owner', $resCheckContent->getMsg()['send-email-to-user']);
        }

        // TODO: add captcha check.

        // -------------------------------------------------------------------- Set the data.
        $firstname = $this->sGlob->getPost('mail-firstname');
        $lastname = $this->sGlob->getPost('mail-lastname');
        $subject = $this->sGlob->getPost('mail-subject');
        $message = $this->sGlob->getPost('mail-content');

        // -------------------------------------------------------------------- Send the email.
        $fromUserMail = $this->userModel->getUserById($fromUserId)->getEmail();

        $resSendMail = $this->userController->sendEmailToOwner(
            $fromUserMail,
            $firstname,
            $lastname,
            $subject,
            $message
        );
        if ($resSendMail->isErr() === true) {
            return $this->res->ko('form-contact-owner', $resSendMail->getMsg()['form-contact-owner']);
        }
        return $this->res->ok('form-contact-owner', $resSendMail->getMsg()['form-contact-owner']);
    }


}
