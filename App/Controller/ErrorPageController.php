<?php

namespace App\Controller;

use App\Entity\Res;

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
        $this->twig->display("pages/page_fo_error.twig", $this->twigData);
    }
}
