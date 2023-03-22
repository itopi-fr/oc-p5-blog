<?php

namespace App\Entity;

use App\Controller\MainController;

class Res
{
    private bool $err;
    private string $type;
    private array $msg;
    private array $result;
    private MainController $mc;

    public function __construct()
    {
        $this->err = false;
        $this->type = "";
        $this->msg = [];
        $this->result = [];
        $this->mc = new MainController();
    }

    public function ko(string $type, string $msg)
    {
        $this->err = true;
        $this->type = $type;
        $this->msg[$type] = $msg;
        $this->result[$type] = null;
        return $this;
    }

    public function ok(string $type, string $msg, mixed $result)
    {
        $this->err = false;
        $this->type = $type;
        $this->msg[$type] = $msg;
        $this->result[$type] = $result;
        return $this;
    }

    public function translateType(string $type)
    {
        switch ($type) {
            case "pseudo":
                return "Pseudo";
                break;
            case "email":
                return "Adresse email";
                break;
            case "avatar":
                return "Avatar";
                break;
            case "cv":
                return "CV de l'auteur";
                break;
            case "photo":
                return "Photo de l'auteur";
                break;
            case "firstname":
                return "Prénom";
                break;
            case "lastname":
                return "Nom";
                break;
            case "catchphrase":
                return "Phrase d'accroche";
                break;
            case "changePass":
                return "Changement de mot de passe";
                break;
            default:
                return $type;
                break;
        }
    }

    public function showMsg(string $msg): string
    {
        switch ($msg) {
            case "pass-format":
                return "Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre";
                break;
            case "register-success":
                return "Inscription réussie";
                break;
            case "pass-not-match":
                return "Les mots de passe ne correspondent pas";
                break;
            default:
                return $msg;
                break;
        }
    }

    /** --------------------------------------------- Getters & Setters --------------------------------------------- */
    /**
     * @return bool
     */
    public function isErr(): bool
    {
        return $this->err;
    }

    /**
     * @param bool $err
     */
    public function setErr(bool $err): void
    {
        $this->err = $err;
    }

    /**
     * @return array
     */
    public function getMsg(): array
    {
        return $this->msg;
    }

    /**
     * @param array $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult(mixed $result): void
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

}


