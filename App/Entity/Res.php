<?php

namespace App\Entity;

use Exception;

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
 * @package App\Entity
 */
class Res
{
    /**
     * @var bool
     */
    private bool $err;

    /**
     * @var array
     */
    private array $msg;

    /**
     * @var array
     */
    private array $result;

    /**
     * @var array|mixed
     */
    private array $types;

    /**
     * @var array|mixed
     */
    private array $messages;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->err = false;
        $this->msg = [];
        $this->result = [];
        $jsonMessages = file_get_contents(__DIR__ . "/../locale/fr.json");
        $this->messages = json_decode($jsonMessages, true)["show-message"];
        $this->types = json_decode($jsonMessages, true)["show-type"];
    }


    /**
     * Builds an error response object with the given parameters.
     *
     * @param string $type - Error type (login, post-creation, etc.). Used to translate the error message.
     * @param string $msg - Error message.
     * @return $this
     */
    public function ko(string $type, string $msg, Exception $exception = null): self
    {
        $this->err = true;
        $this->msg[$type] = $msg;
        $this->result[$type] = null;
        return $this;
    }


    /**
     * Builds a success response object with the given parameters.
     *
     * @param string $type - Success type (login, post-creation, etc.). Used to translate the success message.
     * @param string $msg - Success message.
     * @param mixed $result - Result of the operation (post, user, etc.). Can be null.
     * @return self
     */
    public function ok(string $type, string $msg, mixed $result = null): self
    {
        $this->msg[$type] = $msg;
        $this->result[$type] = $result;
        return $this;
    }


    /**
     * Translates the error type into a readable message. If no translation is found, the raw error type is returned.
     * Loads the translations from the [language].json file (/locale/[language].json).
     *
     * @param string $type - Error type (login, post-creation, etc.).
     * @return string
     */
    public function showType(string $type): string
    {
        return $this->types[$type] ?? $type;
    }


    /**
     * Translates the error code into a readable message. If no translation is found, the raw error code is returned.
     * Loads the translations from the [language].json file (/locale/[language].json).
     *
     * @param string $msg - Error code (login-successful, post-creation-successful, etc.).
     * @return string
     */
    public function showMsg(string $msg): string
    {
        return $this->messages[$msg] ?? $msg;
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
     * @return void
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
     * @return void
     */
    public function setMsg(array $msg): void
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
     * @return void
     */
    public function setResult(mixed $result): void
    {
        $this->result = $result;
    }
}
