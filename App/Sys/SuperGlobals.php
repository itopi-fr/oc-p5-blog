<?php

namespace App\Sys;

class SuperGlobals
{
    private array $sgEnv;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defineSg();
    }


    /**
     * Get $_ENV
     * @return array|null
     */
    public function getEnvAll(): array
    {
        return $this->sgEnv;
    }


    public function getEnv(string $varName): string|null
    {
        return $this->sgEnv[$varName] ?? null;
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
    }


}