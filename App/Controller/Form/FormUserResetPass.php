<?php

namespace App\Controller\Form;

use App\Controller\MailController;
use App\Controller\UserController;
use App\Entity\Res;
use App\Model\UserModel;
use App\Controller\TokenController;

class FormUserResetPass extends FormController
{
    private UserModel $userModel;
    private TokenController $tokenController;
    protected UserController $userController;
//    protected FormUserChangePass $formUserChangePass;
    private Res $res;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->tokenController = new TokenController();
        $this->userController = new UserController();
        $this->res = new Res();
    }


    /**
     * Treat the form to ask for a password reset
     * @param string $email
     * @return Res
     */
    public function treatFormPassAsk(string $email): Res
    {
        // Check email.
        $resCheckEmail = $this->checkPostedEmail('user-reset-pass', 'email', 6, 254);
        if ($resCheckEmail->isErr() === true) {
            $this->res->ko('user-reset-pass', 'user-reset-pass-ko-email');
            return $this->res;
        }

        // Check if a user with this email exists.
        $user = $this->userModel->getUserByEmail($email);
        if ($user === null) {
            $this->res->ko('user-reset-pass', 'user-reset-pass-ko-email-not-found');
            return $this->res;
        }

        // Create a new token.
        $token = $this->tokenController->createUserToken($user->getUserId(), 'reset-pass')->getResult()['token'];

        // Build mail content.
        $mailTo = $user->getEmail();
        $mailToName = $user->getPseudo();
        $mailSubject = "Réinitialisation de votre mot de passe";
        $mailContent = "Bonjour " . $user->getPseudo() . ",<br><br>";
        $mailContent .= "Une demande de réinitialisation de mot de passe a été demandée pour votre compte.<br />";
        $mailContent .= "Si vous n'en êtes pas à l'origine, vous pouvez ignorer cet email.<br />";
        $mailContent .= "Sinon, veuillez cliquer sur le lien ci-dessous pour mettre à jour votre mot de passe :<br />";
        $mailContent .= "<a href='http://ocp5blog/user/reset-pass-change/'" .
                            $token . ">Changer mon mot de passe</a><br />";
        $mailContent .= "<br />Cordialement,<br />";
        $mailContent .= "L'équipe de p5blog";

        // TODO : Check result of sendMail before returning ok.
        // Send mail.
        $tokenValidateEmail = new MailController();
        $tokenValidateEmail->sendEmail($mailTo, $mailToName, $mailSubject, $mailContent);

        $this->res->ok('user-reset-pass', 'user-reset-pass-success');
        return $this->res;
    }


    /**
     * Treat the form to change the password after clicking on the link of the reset password email.
     * Check if the token is valid, if a user related exists, then change the password and delete the token.
     * @param string $tokenContent
     * @return Res
     */
    public function treatFormPassChange(string $tokenContent): Res
    {
        // Get token.
        $resToken = $this->tokenController->getToken($tokenContent);
        if ($resToken->isErr() === true) {
            $this->res->ko('user-reset-pass', 'user-reset-pass-ko-token-not-found');
            return $this->res;
        }
        $token = $resToken->getResult()['token'];

        // Get User by Token Content.
        $resUserByToken = $this->userController->getUserByToken($token->getContent());
        if ($resUserByToken->isErr() === true) {
            $this->res->ko('user-reset-pass', 'user-reset-pass-ko-user-by-token');
            return $this->res;
        }
        $user = $resUserByToken->getResult()['user-by-token'];

        // Verify token.
        $resVerifyToken = $this->tokenController->verifyToken($token->getContent(), $user->getEmail());
        if ($resVerifyToken->getMsg()['verify-token'] !== 'verify-token-ok') {
            $this->res->ko('user-reset-pass', 'user-reset-pass-ko-verify-token');
            return $this->res;
        }

        // Change password.
        $resChangePass = (new FormUserChangePass())->treatFormChangePass(
            $user,
            '',
            $this->sGlob->getPost('pass-new-a'),
            $this->sGlob->getPost('pass-new-b'),
            true
        );

        if ($resChangePass->isErr() === true) {
            $this->res->ko('user-reset-pass', $resChangePass->getMsg()['user-change-pass']);
            return $this->res;
        }

        // Delete Token.
        $this->tokenController->deleteTokenById($token->getTokenId());
        $this->res->ok('user-reset-pass', 'user-reset-pass-ok-updated');
        $this->redirectTo('/user/connexion', 5);
        return $this->res;
    }
}
