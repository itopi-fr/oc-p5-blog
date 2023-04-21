<?php

namespace App\Sys;

class SuperGlobals
{
    private array $sgEnv;
    private array $sgSes;
    private array $sgPost;
    private array $sgFiles;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defineSg();
    }


    /**
     * Get all $_ENV variables
     * @return array|null
     */
    public function getEnvAll(): array|null
    {
        return $this->sgEnv;
    }


    /**
     * Get a specific $_ENV variable
     * @param string $varName
     * @return string|null
     */
    public function getEnv(string $varName): string|null
    {
        return $this->sgEnv[$varName] ?? null;
    }


    /**
     * Get all $_SESSION variables
     * @return array|null
     */
    public function getSesAll(): array|null
    {
        return $this->sgSes;
    }


    /**
     * Get a specific $_SESSION variable
     * @param string $varName
     * @return mixed
     */
    public function getSes(string $varName): mixed
    {
        return $this->sgSes[$varName] ?? null;
    }


    /**
     * Set a specific $_SESSION variable
     * @param string $varName
     * @param mixed $varValue
     * @return void
     */
    public function setSes(string $varName, mixed $varValue): void
    {
        $_SESSION[$varName] = $varValue;
    }


    /**
     * Get all $_POST variables
     * @return array|null
     */
    public function getPostAll(): array|null
    {
        return $this->sgPost;
    }


    /**
     * Get a specific $_POST variable
     * @param string $varName
     * @return string|null
     */
    public function getPost(string $varName): string|null
    {
        return $this->sgPost[$varName] ?? null;
    }


    /**
     * Get all $_FILES variables
     * @return array|null
     */
    public function getFilesAll(): array|null
    {
        return $this->sgFiles;
    }


    /**
     * Get a specific $_FILES variable
     * @param string $varName
     * @return array|null
     */
    public function getFiles(string $varName): array|null
    {
        return $this->sgFiles[$varName] ?? null;
    }


    /**
     * Function to define superglobals for use locally.
     * We do not automatically unset the superglobals after
     * defining them, since they might be used by other code.
     *
     * @return void
     */
    private function defineSg(): void
    {
        $this->sgEnv = (isset($_ENV)) ? $_ENV : null;
        $this->sgSes = (isset($_SESSION)) ? $_SESSION : null;
        $this->sgPost = (isset($_POST)) ? $_POST : null;
        $this->sgFiles = (isset($_FILES)) ? $_FILES : null;
    }


}