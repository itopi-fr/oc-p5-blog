<?php

namespace App\Controller\Owner;

use App\Controller\CommentController;
use App\Controller\PostController;
use App\Entity\Post;
use App\Model\PostModel;

class OwnerPostController extends OwnerController
{
    protected array $posts;
    protected PostController $postController;

    public function __construct()
    {
        parent::__construct();
        $this->postController = new PostController();
    }

    public function managePosts(): void
    {
        $this->twigData['title'] = 'Administration des articles';
        $this->twigData['posts'] = $this->postController->getLastPubPosts(100);

        echo  $this->twig->render("pages/owner/page_bo_posts_manage.twig", $this->twigData);
    }

}