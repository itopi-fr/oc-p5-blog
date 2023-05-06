<?php

namespace App\Sys;

/**
 * Class SuperGlobals - Manages the superglobals. Used to avoid direct use of superglobals.
 */
class SuperGlobals
{
    /**
     * @var array
     */
    private array $sgEnv;

    /**
     * @var array
     */
    private array $sgSes;

    /**
     * @var array
     */
    private array $sgGet;

    /**
     * @var array
     */
    private array $sgPost;

    /**
     * @var array
     */
    private array $sgFiles;

    /**
     * @var array
     */
    private array $sgServer;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defineSg();
    }


    /**
     * Get all $_ENV variables.
     *
     * @return array|null
     */
    public function getEnvAll(): array|null
    {
        return $this->sgEnv;
    }


    /**
     * Get a specific $_ENV variable.
     *
     * @param string $varName - The name of the variable to get
     * @return string|null
     */
    public function getEnv(string $varName): string|null
    {
        return $this->sgEnv[$varName] ?? null;
    }


    /**
     * Get all $_SESSION variables.
     *
     * @return array|null
     */
    public function getSesAll(): array|null
    {
        return $this->sgSes;
    }


    /**
     * Get a specific $_SESSION variable.
     *
     * @param string $varName - The name of the variable to get
     * @return mixed
     */
    public function getSes(string $varName): mixed
    {
        return $this->sgSes[$varName] ?? null;
    }


    /**
     * Set a specific $_SESSION variable.
     *
     * @param string $varName - The name of the variable to set
     * @param mixed $varValue - The value of the variable to set
     * @return void
     */
    public function setSes(string $varName, mixed $varValue): void
    {
        $_SESSION[$varName] = $varValue;
        $this->defineSg('ses');
    }


    /**
     * Get all $_GET variables.
     *
     * @return array|null
     */
    public function getGetAll(): array|null
    {
        return $this->sgGet;
    }


    /**
     * Get a specific $_GET variable.
     *
     * @param string $varName - The name of the variable to get
     * @return string|null
     */
    public function getGet(string $varName): string|null
    {
        return $this->sgGet[$varName] ?? null;
    }


    /**
     * Get all $_POST variables.
     *
     * @return array|null
     */
    public function getPostAll(): array|null
    {
        return $this->sgPost;
    }


    /**
     * Get a specific $_POST variable.
     *
     * @param string $varName - The name of the variable to get
     * @return string|null
     */
    public function getPost(string $varName): string|null
    {
        return $this->sgPost[$varName] ?? null;
    }


    /**
     * Get all $_FILES variables.
     *
     * @return array|null
     */
    public function getFilesAll(): array|null
    {
        return $this->sgFiles;
    }


    /**
     * Get a specific $_FILES variable.
     *
     * @param string $varName - The name of the variable to get
     * @return array|null
     */
    public function getFiles(string $varName): array|null
    {
        return $this->sgFiles[$varName] ?? null;
    }


    /**
     * Get all $_SERVER variables.
     *
     * @return array|null
     */
    public function getServerAll(): array|null
    {
        return $this->sgServer;
    }


    /**
     * Get a specific $_SERVER variable.
     *
     * @param string $varName - The name of the variable to get
     * @return string|null
     */
    public function getServer(string $varName): string|null
    {
        return $this->sgServer[$varName] ?? null;
    }


    /**
     * Function to define superglobals for use locally.
     *
     * @param string|null $which - Which superglobal to define (optional)
     * @return void
     */
    private function defineSg(string|null $which = null): void
    {
        if ($which === null) {
            $this->sgEnv = (isset($_ENV) === true) ? $_ENV : null;
            $this->sgSes = (isset($_SESSION) === true) ? $_SESSION : null;
            $this->sgGet = (isset($_GET) === true) ? $_GET : null;
            $this->sgPost = (isset($_POST) === true) ? $_POST : null;
            $this->sgFiles = (isset($_FILES) === true) ? $_FILES : null;
            $this->sgServer = (isset($_SERVER) === true) ? $_SERVER : null;
        } else {
            switch ($which) {
                case 'env':
                    $this->sgEnv = (isset($_ENV) === true) ? $_ENV : null;
                    break;
                case 'ses':
                    $this->sgSes = (isset($_SESSION) === true) ? $_SESSION : null;
                    break;
                case 'get':
                    $this->sgGet = (isset($_GET) === true) ? $_GET : null;
                    break;
                case 'post':
                    $this->sgPost = (isset($_POST) === true) ? $_POST : null;
                    break;
                case 'files':
                    $this->sgFiles = (isset($_FILES) === true) ? $_FILES : null;
                    break;
                case 'server':
                    $this->sgServer = (isset($_SERVER) === true) ? $_SERVER : null;
                    break;
            }
        }
    }


}
