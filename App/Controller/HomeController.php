<?php

namespace App\Controller;

use App\Model\PostModel;

class HomeController extends MainController
{
    protected PostModel $postModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
    }

    public function index()
    {
        $this->twigData['lastposts'] = $this->postModel->getLastPubPosts(2);
        echo  $this->twig->render("pages/page_fo_home.twig", $this->twigData);
    }


}