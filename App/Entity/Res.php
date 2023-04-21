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
            case "user-login":
                return "Connexion";
                break;
            case "register":
                return "Inscription";
                break;
            case "user-profile":
                return "Profil utilisateur";
                break;
            case "owner-profile":
                return "Profil de l'auteur";
                break;
            case "user-change-pass":
                return "Mise à jour du mot de passe";
                break;
            case "user-reset-pass":
                return "Réinitialisation du mot de passe";
                break;
            case "post-edit":
                return "Édition d'un article";
                break;
            case "post-create":
                return "Nouvel article";
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
            // -------------------------------------------------------------------- user-login
            case "user-login-ok-success":
                return "Connexion réussie, redirection vers votre profil";
                break;
            case "user-login-ko-no-user-pass-match":
            case "user-by-email-not-found":
                return "Échec de la connexion : aucun utilisateur ne correspond à ces identifiants";
                break;
            case "user-login-ko-account-token-not-validated":
                return "Échec de la connexion : 
                        veuillez valider votre compte en cliquant sur le lien contenu dans l'email de confirmation";
                break;
            // -------------------------------------------------------------------- register
            case "register-success-wait-mail-confirm":
                return "Demande d'inscription réussie. Un email de confirmation vous a été envoyé.
                        Veuillez cliquer sur le lien contenu dans cet email pour activer votre compte.";
                break;
            // -------------------------------------------------------------------- user-profile
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
            // ----------------------------------------------------------------- user-change-pass
            case "user-change-pass-ok-updated":
                return "Mot de passe changé";
                break;
            case "user-change-pass-ko-wrong-format":
                return "Le format du mot de passe n'est pas correct : doit être composé de 8 à 30 caractères,
                dont au moins une lettre minuscule, une lettre majuscule et un chiffre";
                break;
            case "user-change-pass-ko-pass-dont-match":
                return "Les nouveaux mots de passe saisis ne correspondent pas";
                break;
            case "user-change-pass-ko-old-pass-incorrect":
                return "L'ancien mot de passe est incorrect";
                break;
            case "user-reset-pass-ok-updated":
                return "Mot de passe défini avec succès. Redirection en cours...";
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
            // -------------------------------------------------------------------- send-email
            case "send-email-success":
                return "Email envoyé avec succès";
                break;
            case "send-email-failed":
                return "Erreur lors de l'envoi de l'email";
                break;
            // -------------------------------------------------------------------- user-reset-pass
            case "user-reset-pass-success":
                return "Un email vous a été envoyé pour réinitialiser votre mot de passe";
                break;
            case "user-reset-pass-ko-email-not-found":
                return "Aucun compte associé à cette adresse email";
                break;
            case "user-reset-pass-ko-token-not-found":
            case "user-reset-pass-ko-verify-token":
                return "Demande non valide. Veuillez redemander un email de réinitialisation de mot de passe";
                break;
            case "user-reset-pass-ko-user-by-token":
                return "Aucun compte associé à ce token";
                break;
            // -------------------------------------------------------------------- post-edit
            case "post-edit-ok":
                return "Article mis à jour avec succès";
                break;
            case "post-edit-ko-post-slug-empty":
                return "Veuillez saisir un slug";
                break;
            case "post-edit-ko-post-slug-not-alpha-num-dash":
                return "Slug : doit être composé uniquement de lettres, chiffres et tirets";
                break;
            case "post-edit-ko-post-slug-not-between-3-and-64":
                return "Slug : doit être composé de 3 à 64 caractères";
                break;
            case "post-edit-ko-post-slug-not-unique":
                return "Slug : ce slug est déjà utilisé";
                break;
            case "post-edit-ko-post-content-not-alpha-num-punct":
                return "Contenu : doit être composé de lettres, chiffres, espaces, ponctuation et sauts de ligne";
                break;
            // -------------------------------------------------------------------- post-create
            case "post-create-ok":
                return "Article créé avec succès";
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
