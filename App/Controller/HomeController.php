<?php

namespace App\Controller;

class HomeController extends MainController
{
    protected PostController $postController;

    public function __construct()
    {
        parent::__construct();
        $this->postController = new PostController();
    }

    public function index()
    {
        $this->twigData['lastposts'] = $this->postController->getLastPubPosts();
//        $this->dump($this->twigData);
        echo  $this->twig->render("pages/page_fo_home.twig", $this->twigData);
    }


}