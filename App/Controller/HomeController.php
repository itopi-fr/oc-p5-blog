<?php

namespace App\Controller;

use App\Model\UserModel;

class HomeController extends MainController
{

    private UserModel $dbTest;
    private array $test;

    public function __construct()
    {
        $this->dbTest = new UserModel();
        $this->test = $this->dbTest->getAllUsers();
        $this->dump($this->test);
    }

    private array $twigData = ['data1' => 'données 1', 'data2' => 'données 2'];

    public function index()
    {
        $this->twigData['posts'] = $this->posts;
        $this->initTwig();
        echo  $this->twig->render("pages/fo/fo_home.twig", $this->twigData);
    }
}