<?php

namespace App\Controller;

class ErrorPageController extends MainController
{


    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        echo  $this->twig->render("pages/fo/fo_error.twig", $this->twigData);
    }
}