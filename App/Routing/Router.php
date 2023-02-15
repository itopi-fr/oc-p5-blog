<?php

namespace App\Routing;

use App\Controller\HomeController;

class Router
{

    public function __construct()
    {
        // echo "App\Routing\Router";
    }


    public function run() : void
    {
        $url = $_SERVER['REQUEST_URI'];

        switch ($url) {
            case '/':
                $controller = new HomeController();
                $controller->index();
                break;

            default:
                echo '404';
                break;
        }
    }
}