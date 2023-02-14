<?php

namespace App;

use App\Home\HomeController;

class Router
{

    public function __construct()
    {
        echo "App\Router";
    }


    public function run()
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