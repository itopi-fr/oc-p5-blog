<?php

namespace App\Controller;

class HomeController
{
    public function __construct()
    {

    }

    private function dump($var)
    {
        //style='background-color: #000000; color: #0b5ed7'
        echo "<pre class='vardump'>";
        var_dump($var);
        echo "</pre>";
    }

    public function index()
    {
        $this->dump("App\Controllers\HomeController::index");
    }
}