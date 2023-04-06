<?php

namespace App\Controller;

use App\Entity\Res;
use Exception;

class ErrorPageController extends MainController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function index(RES $res)
    {
        $this->twigData['result'] = $res;
//        var_dump($res);
        echo  $this->twig->render("pages/page_fo_error.twig", $this->twigData);
    }
}