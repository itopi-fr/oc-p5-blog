<?php

namespace App\Routing;

use App\Controller\HomeController;
use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Controller\PostController;
//use App\Controller\ProfileController;
use App\Controller\UserController;
use Exception;


class Router
{
    private MainController $mc;


    /**
     * Contains the URL parts
     * @var array
     */
    private array $urlParts;
    private string $userAction;
    private string $userActionSub;


    public function __construct()
    {
        $this->mc = new MainController();
    }


    public function run(): void
    {

        try {
            $this->userActionSub = '';
            $this->urlParts = explode('/', $_GET['p']);
            $pageBase = $this->urlParts[0];

            if (isset($this->urlParts[1]) && !empty($this->urlParts[1])) {
                $this->userAction = $this->urlParts[1];
            }

            if (($this->urlParts[0] == 'user' || 'test') && (empty($this->urlParts[1]))) {
                $this->userAction = 'home';
            }

            // Activation
            if ($this->urlParts[0] == 'user' && $this->urlParts[1] == 'activation') {
                if (isset($this->urlParts[2]) && !empty($this->urlParts[2])) {
                    $this->userActionSub = $this->urlParts[2];
                }
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
                    $controller->single($this->userAction);
                    break;

                case ('user'):
                    $controller = new UserController();
                    $controller->index($this->userAction, $this->userActionSub);        // /!\ Ajouter un check
                    break;

//                case ('err'):
//                    $controller = new ErrorPageController();
//                    $controller->index();
//                    break;


                default:
                    $controller = new ErrorPageController();
                    $controller->index();
                    break;
            }
        } catch (Exception $e) {
            if($_ENV['MODE_DEV'] == 'true') {
                $this->mc->dump($e);
            }

        }


    }
}