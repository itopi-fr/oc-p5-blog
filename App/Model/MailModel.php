<?php

namespace App\Model;

use App\Sys\SuperGlobals;
use App\Entity\Res;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailModel
{
    protected Res $res;

    private SuperGlobals $superGlobals;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->res = new Res();
        $this->superGlobals = new SuperGlobals();
    }

    /**
     * Send an email with the PHPMailer library.
     * @param string $mailTo
     * @param string $mailToName
     * @param string $subject
     * @param string $msgHtml
     * @param string $msgRaw
     * @return Res
     * @throws Exception
     */
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
        $mail->IsHTML();
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
            return $this->res->ok('send-email', 'send-email-success');
        } else {
            return $this->res->ko('send-email', 'send-email-failed');
        }
    }
}
