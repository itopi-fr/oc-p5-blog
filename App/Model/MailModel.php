<?php

namespace App\Model;

use App\Controller\MainController;
use App\Sys\SuperGlobals;
use App\Entity\Res;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailModel
{
    protected Res $res;
    private SuperGlobals $superGlobals;
//    protected MainController $mc;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->res = new Res();
        $this->superGlobals = new SuperGlobals();
//        $this->mc = new MainController();
    }

    public function sendEmailSmtp(
        string $mailTo,
        string $mailToName,
        string $subject,
        string $msgHtml,
        string $msgRaw
    ): Res {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth =   1;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet =    'UTF-8';
        $mail->WordWrap   = 50;
        $mail->IsHTML(true);
        $mail->Host =       $this->superGlobals->getEnv('MAIL_HOST');
        $mail->Port =       $this->superGlobals->getEnv('MAIL_PORT');
        $mail->Username =   $this->superGlobals->getEnv('MAIL_USER');
        $mail->Password =   $this->superGlobals->getEnv('MAIL_PASS');
        $mail->From =       $this->superGlobals->getEnv('MAIL_FROM');
        $mail->FromName =   $this->superGlobals->getEnv('MAIL_FROM_NAME');

        $mail->AddAddress($mailTo, $mailToName);
        $mail->Subject =  $subject;
        $mail->AltBody = $msgRaw;
        $mail->MsgHTML($msgHtml);
        $mail->smtpConnect();

        if ($mail->send()) {
            return $this->res->ok('send-email', 'send-email-success', null);
        } else {
            return $this->res->ko('send-email', 'send-email-failed');
        }
    }
}