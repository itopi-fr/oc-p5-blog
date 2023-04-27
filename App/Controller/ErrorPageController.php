<?php

namespace App\Controller;

use App\Entity\Res;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ErrorPageController - Error page tools.
 */
class ErrorPageController extends MainController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Display the error page.
     * @param Res $res
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(RES $res)
    {
        $this->twigData['result'] = $res;
        $this->twig->display("pages/page_fo_error.twig", $this->twigData);
    }
}
