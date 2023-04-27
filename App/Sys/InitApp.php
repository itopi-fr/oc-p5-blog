<?php

namespace App\Sys;

use App\Routing\Router;
use Dotenv\Dotenv;

class InitApp
{
    /**
     * Init the app
     * @return void
     */
    public function init(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->load();
        $sGlob = new SuperGlobals();

        // Error reporting.
        if ($sGlob->getEnv('MODE_DEV') === 'true') {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        }

        // Routing.
        $route = new Router();
        $route->run();
    }


}
