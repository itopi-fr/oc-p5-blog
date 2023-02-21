<?php

namespace App\Controller;

class ErrorPageController extends MainController
{
    private array $twigData = ['data1' => 'données 1', 'data2' => 'données 2'];
    public function index()
    {
        $this->initTwig();
        echo  $this->twig->render("pages/fo/fo_error.twig", $this->twigData);
    }
}