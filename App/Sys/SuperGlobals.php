<?php

namespace App\Sys;

class SuperGlobals
{
    private array $sgEnv;
    private array $sgSes;

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
     * Get a specific $_ENV variable
     * @param string $varName
     * @return string|null
     */
    public function getSes(string $varName): string|null
    {
        return $this->sgSes[$varName] ?? null;
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
    }


}