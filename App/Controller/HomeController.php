<?php

namespace App\Controller;

use App\Model\PostModel;

/**
 * Class HomeController - Home page tools.
 */
class HomeController extends MainController
{
    /**
     * @var PostModel
     */
    protected PostModel $postModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
    }

    /**
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $this->twigData['lastposts'] = $this->postModel->getLastPubPosts(2);
        $this->twig->display("pages/page_fo_home.twig", $this->twigData);
    }
}
