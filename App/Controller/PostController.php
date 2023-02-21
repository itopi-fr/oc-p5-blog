<?php

namespace App\Controller;

class PostController extends MainController
{
    /**
     * @var array|string[]
     */
    private array $twigData = ['data1' => 'données 1', 'data2' => 'données 2'];

    public function index()
    {
        $this->twigData['posts'] = $this->posts;
        $this->initTwig();
        echo  $this->twig->render("pages/fo/fo_posts.twig", $this->twigData);
    }

    private function postExists($postId) : bool
    {
        return isset($this->posts[$postId]);
    }

    public function single($postId)
    {
        $this->twigData['posts'] = $this->posts;

        if (!$this->postExists($postId)) {

            // /!\ Créer une classe Error
            $this->twigData['error'] = ['code' => 404, 'title' => 'erreur article', 'message' => "L'article n'existe pas"];


            $this->initTwig();
            echo  $this->twig->render("pages/fo/fo_error.twig", $this->twigData);
            return;
        }

        $this->twigData['post'] = $this->posts[$postId];      // /!\ Ajouter un check
        $this->initTwig();
        echo  $this->twig->render("pages/fo/fo_post_single.twig", $this->twigData);
    }
}