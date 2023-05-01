<?php

namespace App\Controller;

use App\Entity\Res;
use App\Model\MailModel;
use Exception;

/**
 * Class MailController - Mail functions.
 */
class MailController extends MainController
{
    /**
     * @var MailModel
     */
    protected MailModel $mailModel;

    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var string
     */
    protected string $smtpHost;

    /**
     * @var int
     */
    protected int $smtpPort;

    /**
     * @var string
     */
    protected string $smtpUser;

    /**
     * @var string
     */
    protected string $smtpPass;

    /**
     * @var string
     */
    protected string $mailFrom;

    /**
     * @var string
     */
    protected string $mailFromName;

    /**
     * @var string
     */
    protected string $mailTo;

    /**
     * @var string
     */
    protected string $mailToName;

    /**
     * @var string
     */
    protected string $mailSubject;

    /**
     * @var string
     */
    protected string $mailMessageRaw;

    /**
     * @var string
     */
    protected string $mailMessageHtml;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
    }


    /**
     * Sends an email using SMTP protocol with authentification (SSL)
     * Verify if all required fields are set and if they are valid
     * @param string $to
     * @param string $toName
     * @param string $subject
     * @param string $messageHtml
     * @return Res|void
     */
    public function sendEmail(string $to, string $toName, string $subject, string $messageHtml)
    {
        try {
            $this->mailTo = $to;
            $this->mailToName = $toName;
            $this->mailSubject = $subject;
            $this->mailMessageHtml = $messageHtml;
            $this->mailMessageRaw = $this->generateRawMessage($messageHtml);

            // Check mailTo.
            if ($this->isSet($this->mailTo) === false) {
                return $this->res->ko("send-email", 'send-email-ko-missing-mail-to');
            } elseif (!$this->isEmail($this->mailTo)) {
                return $this->res->ko("send-email", 'send-email-ko-invalid-format-mail-to');
            }

            // Check mailToName.
            if ($this->isSet($this->mailToName) === false) {
                return $this->res->ko("send-email", 'send-email-ko-missing-mail-to-name');
            } elseif (!$this->isAlphaNumSpacesPunct($this->mailToName)) {
                return $this->res->ko("send-email", 'send-email-ko-invalid-format-mail-to-name');
            }

            // Check mailSubject.
            if ($this->isSet($this->mailSubject) === false) {
                return $this->res->ko("send-email", 'send-email-ko-missing-mail-subject');
            }

            $this->mailModel = new MailModel();
            $this->mailModel->sendEmailSmtp(
                $to,
                $toName,
                $subject,
                $messageHtml,
                $this->generateRawMessage($messageHtml)
            );
            return $this->res->ok("send-email", 'send-email-ok');
        } catch (Exception $e) {
            return $this->res->ko("send-email", 'send-email-ko-exception', $e);
        }
    }


    /**
     * Generates a raw message from a HTML message
     * @param string $messageHtml
     * @return string
     */
    protected function generateRawMessage(string $messageHtml): string
    {
        $messageRaw = str_replace(array("<br>", "<br/>", "<br />"), "\r\n", $messageHtml);
        $messageRaw = preg_replace("/<a href=\"(.*)\">.*<\/a>/", "$1", $messageRaw);
        $this->mailMessageRaw = strip_tags($messageRaw);
        return $this->mailMessageRaw;
    }


    /** --------------------------------------------- Getters & Setters --------------------------------------------- */
    /**
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->smtpHost;
    }

    /**
     * @param string $smtpHost
     */
    public function setSmtpHost(string $smtpHost): void
    {
        $this->smtpHost = $smtpHost;
    }

    /**
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->smtpPort;
    }

    /**
     * @param int $smtpPort
     */
    public function setSmtpPort(int $smtpPort): void
    {
        $this->smtpPort = $smtpPort;
    }

    /**
     * @return string
     */
    public function getSmtpUser(): string
    {
        return $this->smtpUser;
    }

    /**
     * @param string $smtpUser
     */
    public function setSmtpUser(string $smtpUser): void
    {
        $this->smtpUser = $smtpUser;
    }

    /**
     * @return string
     */
    public function getSmtpPass(): string
    {
        return $this->smtpPass;
    }

    /**
     * @param string $smtpPass
     */
    public function setSmtpPass(string $smtpPass): void
    {
        $this->smtpPass = $smtpPass;
    }

    /**
     * @return string
     */
    public function getMailFrom(): string
    {
        return $this->mailFrom;
    }

    /**
     * @param string $mailFrom
     */
    public function setMailFrom(string $mailFrom): void
    {
        $this->mailFrom = $mailFrom;
    }

    /**
     * @return string
     */
    public function getMailFromName(): string
    {
        return $this->mailFromName;
    }

    /**
     * @param string $mailFromName
     */
    public function setMailFromName(string $mailFromName): void
    {
        $this->mailFromName = $mailFromName;
    }

    /**
     * @return string
     */
    public function getMailTo(): string
    {
        return $this->mailTo;
    }

    /**
     * @param string $mailTo
     */
    public function setMailTo(string $mailTo): void
    {
        $this->mailTo = $mailTo;
    }

    /**
     * @return string
     */
    public function getMailToName(): string
    {
        return $this->mailToName;
    }

    /**
     * @param string $mailToName
     */
    public function setMailToName(string $mailToName): void
    {
        $this->mailToName = $mailToName;
    }

    /**
     * @return string
     */
    public function getMailSubject(): string
    {
        return $this->mailSubject;
    }

    /**
     * @param string $mailSubject
     */
    public function setMailSubject(string $mailSubject): void
    {
        $this->mailSubject = $mailSubject;
    }

    /**
     * @return string
     */
    public function getMailMessageRaw(): string
    {
        return $this->mailMessageRaw;
    }

    /**
     * @param string $mailMessageRaw
     */
    public function setMailMessageRaw(string $mailMessageRaw): void
    {
        $this->mailMessageRaw = $mailMessageRaw;
    }

    /**
     * @return string
     */
    public function getMailMessageHtml(): string
    {
        return $this->mailMessageHtml;
    }

    /**
     * @param string $mailMessageHtml
     */
    public function setMailMessageHtml(string $mailMessageHtml): void
    {
        $this->mailMessageHtml = $mailMessageHtml;
    }
}
