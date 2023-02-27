<?php

namespace App\Controller;

class PostController extends MainController
{
    /**
     * @var array|string[]
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo  $this->twig->render("pages/fo/fo_posts.twig", $this->twigData);
    }

    private function postExists($postId) : bool
    {
        return isset($this->twigData['posts'][$postId]);
    }

    public function single($postId)
    {
        if (!$this->postExists($postId)) {

            // /!\ Créer une classe Error
            $this->twigData['error'] = ['code' => 404, 'title' => 'erreur article', 'message' => "L'article n'existe pas"];


            echo  $this->twig->render("pages/fo/fo_error.twig", $this->twigData);
            return;
        }

        $this->twigData['post'] = $this->twigData['posts'][$postId];      // /!\ Ajouter un check
        echo  $this->twig->render("pages/fo/fo_post_single.twig", $this->twigData);
    }
}