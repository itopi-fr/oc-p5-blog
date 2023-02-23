<?php

namespace App\Routing;

use App\Controller\HomeController;
use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Controller\PostController;
use App\Controller\ProfileController;
use App\Controller\UserController;


class Router
{
    private MainController $mc;


    /**
     * Contains the URL parts
     * @var array
     */
    private array $urlParts;


    public function __construct()
    {
        $this->mc = new MainController();
    }


    public function run() : void
    {

        try {
            $this->urlParts = explode('/', $_GET['p']);
            $pageBase = $this->urlParts[0];

            if ($this->urlParts[0] == 'user') {
                if (isset($this->urlParts[1])) {
                    $userAction = $this->urlParts[1];
                } else {
                    $userAction = 'home';
                }
            } elseif ($this->urlParts[0] == 'article') {
                $articleId = $this->urlParts[1];
            } elseif ($this->urlParts[0] == 'owner') {
                $pageBase = 'own_' . $this->urlParts[1];
            }

            switch ($pageBase) {
                case (''):
                    $controller = new HomeController();
                    $controller->index();
                    break;

                case ('articles'):
                    $controller = new PostController();
                    $controller->index();
                    break;

                case ('article'):
                    $controller = new PostController();
                    $controller->single($articleId);        // /!\ Ajouter un check
                    break;

                case ('user'):
                    $controller = new UserController();
                    $controller->index($userAction);        // /!\ Ajouter un check
                    break;

                case ('own_articles'):
                    $controller = new OwnerPostController();
                    $controller->index();
                    break;

                default:
                    $controller = new ErrorPageController();
                    break;
            }
        } catch (\Throwable $th) {
            $this->mc->dump($th);
        }


    }
}