<?php

namespace App\Model;

use App\Controller\MainController;
use App\Entity\Res;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailModel
{
    protected Res $res;
//    protected MainController $mc;

    public function sendEmailSmtp(string $to, string $toName, string $subject, string $messageHtml, string $messageRaw): Res
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth =   1;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet =    'UTF-8';
        $mail->WordWrap   = 50;
        $mail->IsHTML(true);
        $mail->Host =       $_ENV['MAIL_HOST'];
        $mail->Port =       $_ENV['MAIL_PORT'];
        $mail->Username =   $_ENV['MAIL_USER'];
        $mail->Password =   $_ENV['MAIL_PASS'];
        $mail->From =       $_ENV['MAIL_FROM'];
        $mail->FromName =   $_ENV['MAIL_FROM_NAME'];

        $mail->AddAddress($to, $toName);
        $mail->Subject =  $subject;
        $mail->AltBody = $messageRaw;
        $mail->MsgHTML($messageHtml);
        $mail->smtpConnect();

        if ($mail->send()) {
            return $this->res->ok('send-email', 'send-email-success', null);
        } else {
            return $this->res->ko('send-email', 'send-email-failed');
        }
    }

    public function __construct()
    {
        $this->res = new Res();
//        $this->mc = new MainController();
    }

}