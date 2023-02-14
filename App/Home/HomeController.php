<?php

namespace App\Home;

class HomeController
{
    public function __construct()
    {

    }

    private function dump($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    public function index()
    {
        $this->dump("App\Home\HomeController::index");
    }
}