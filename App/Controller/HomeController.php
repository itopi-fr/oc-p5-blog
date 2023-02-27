<?php

namespace App\Controller;

class HomeController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->twigData['posts'] = $this->posts;
        echo  $this->twig->render("pages/fo/fo_home.twig", $this->twigData);
    }
}