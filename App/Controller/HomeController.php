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
        echo  $this->twig->render("pages/fo/fo_home.twig", $this->twigData);
    }
}