<?php

namespace App\Entity;

/**
 * Represents a response object.
 * Used to return a response with a status (true if error, false if success), a message and a result (if success).
 * The message and the result are arrays, so that multiple messages and results can be returned.
 *
 * The message and the result are associated by their type, ex :
 *      $this->msg['post-creation'] = 'Post has been created';
 *      $this->result[$type] = {post_id: 1, post_title: 'My post', post_content: 'My post content'};
 *
 * To return a success response, use the ok() method :
 *     $res->ok('login', 'Login successful', $user);
 * To return an error response, use the ko() method :
 *    $res->ko('login', 'Login failed');
 *
 * The class also provides a method to translate the error message into a readable message.
 * To use it, pass the error type to the showMsg() method :
 *    $res->showMsg('login-successful');
 * The method will return the translated message if it exists, or the raw message if no translation is found.
 * TODO: add an external yml file to manage translations and import it in the class.
 * @package App\Entity
 */
class Res
{
    private bool $err;
    private array $msg;
    private array $result;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->err = false;
        $this->msg = [];
        $this->result = [];
    }

    public function ko(string $type, string $msg)
    {
        $this->err = true;
        $this->msg[$type] = $msg;
        $this->result[$type] = null;
        return $this;
    }

    /**
     * Builds a success response object with the given parameters.
     * @param string $msg
     * @param mixed $result
     * @return $this
     */
    public function ok(string $type, string $msg, mixed $result)
    {
        $this->msg[$type] = $msg;
        $this->result[$type] = $result;
        return $this;
    }

    /**
     * Translates the error type into a readable message. If no translation is found, the raw error type is returned.
     * @param string $type
     * @return string
     */
    public function showType(string $type)
    {
        switch ($type) {
            case "general":
                return "Général";
                break;
            case "user-profile":
                return "Profil utilisateur";
                break;
            case "owner-profile":
                return "Profil de l'auteur";
                break;
            default:
                return $type;
                break;
        }
    }

    /**
     * Translates the error code into a readable message. If no translation is found, the raw error code is returned.
     * @param string $msg
     * @return string
     */
    public function showMsg(string $msg): string
    {
        switch ($msg) {
            // -------------------------------------------------------------------- user-profile
            // General
            case "user-profile-ok-no-change":
                return "Aucun changement apporté au profil";
                break;
            case "user-profile-ok-updated":
                return "Le profil a bien été mis à jour";
                break;
            case "user-profile-ko-error":
                return "Mise à jour du profil : une erreur est survenue";
                break;
            // Pseudo
            case "user-profile-ko-pseudo-empty":
                return "Veuillez saisir un pseudo";
                break;
            case "user-profile-ko-pseudo-not-between- 4-and-30":
                return "Pseudo : doit être compris entre 4 et 30 caractères";
                break;
            case "user-profile-ko-pseudo-not-alpha-num-plus":
                return "Pseudo : doit être composé uniquement de lettres, chiffres et tirets";
                break;
            case "user-profile-ko-pseudo-not-unique":
                return "Ce pseudo déjà utilisé";
                break;
            // Email
            case "user-profile-ko-email-empty":
                return "Veuillez saisir une adresse email";
                break;
            case "user-profile-ko-email-not-email-format":
                return "L'adresse email n'est pas au bon format";
                break;
            case "user-profile-ko-email-not-unique":
                return "Cette adresse email est déjà utilisée";
                break;
            // File Avatar
            case "user-profile-file-avatar-ko-missing":
                return "Avatar : aucun fichier envoyé";
                break;
            case "user-profile-file-avatar-ko-not-image":
                return "Avatar : le fichier n'est pas une image valide (jpg, jpeg, png, gif)";
                break;
            // -------------------------------------------------------------------- owner-profile
            case "owner-profile-ok-no-change":
                return "Aucun changement apporté aux informations de l\'auteur";
                break;
            case "owner-profile-ok-updated":
                return "Le profil de l'auteur a bien été mis à jour";
                break;
            case "owner-profile-ko-error":
                return "Mise à jour du profil de l'auteur : une erreur est survenue";
                break;
            case "owner-profile-ko-cv-file-empty":
                return "CV : veuillez renseigner un fichier";
                break;
            case "owner-profile-ko-cv-file-not-pdf":
                return "CV : le fichier n\'est pas un pdf";
                break;
            case "owner-profile-ko-cv-file-too-big":
                return "CV : le fichier est trop volumineux";
                break;
            case "owner-profile-ko-photo-file-empty":
                return "Photo : veuillez renseigner un fichier";
                break;
            case "owner-profile-ko-photo-file-not-image":
                return "Photo : le fichier n\'est pas une image";
                break;
            case "owner-profile-ko-photo-file-too-big":
                return "Photo : le fichier est trop volumineux";
                break;
            // -------------------------------------------------------------------- user-pass
            case "pass-format":
                return "Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre";
                break;
            case "pass-not-match":
                return "Les mots de passe ne correspondent pas";
                break;
            // -------------------------------------------------------------------- user-register
            case "register-success":
                return "Inscription réussie";
                break;
            // -------------------------------------------------------------------- user-token
            case "token-content-not-found":
                return "Ce token n'existe pas ou est expiré";
                break;
            // -------------------------------------------------------------------- default
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
}
