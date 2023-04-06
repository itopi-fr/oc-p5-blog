<?php

namespace App\Routing;

use App\Controller\HomeController;
use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Controller\OwnerInfoController;
use App\Controller\PostController;
//use App\Controller\ProfileController;
use App\Controller\UserController;
use App\Entity\Res;
use Exception;

class Router
{
    private MainController $mc;


    /**
     * Contains the URL parts
     * @var array
     */
    private array $urlParts;
    private string $pageBase;
    private string $pageAction = '';
    private string $pageActionParam = '';
    private OwnerInfoController $ownerInfoController;
    private Res $res;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mc = new MainController();
        $this->res = new Res();
    }


    public function run(): void
    {

        try {
            // TODO: Revoir ce système de routing (.htaccess)

            // Extract URL parts
            $this->urlParts =           explode('/', $_GET['p']);
            $this->pageBase =           (array_key_exists(0, $this->urlParts)) ? $this->urlParts[0] : 'home';
            $this->pageAction =         (array_key_exists(1, $this->urlParts)) ? $this->urlParts[1] : '';
            $this->pageActionParam =    (array_key_exists(2, $this->urlParts)) ? $this->urlParts[2] : '';

            // Owner info
            if (
                (isset($_SESSION['ownerinfo']) === false) ||
                (empty($_SESSION['ownerinfo']) === true) ||
                ($_SESSION['ownerinfo'] == null)
            ) {
                $this->ownerInfoController = new OwnerInfoController();
                $ownerInfo = $this->ownerInfoController->getOwnerInfo();
                $_SESSION['ownerinfo'] = $ownerInfo;
            }

            // Route
            switch ($this->pageBase) {
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
                    $controller->single($this->pageAction);
                    break;

                case ('user'):
                    $controller = new UserController();
                    $controller->index($this->pageAction, $this->pageActionParam);
                    break;

                default:
                    $this->res->ko('general', "La page demandée n'existe pas");
                    $controller = new ErrorPageController();
                    $controller->index($this->res);
                    break;
            }
        } catch (Exception $e) {
            if ($_ENV['MODE_DEV'] == 'true') {
                $this->mc->dump($e);
            } else {
                $controller = new ErrorPageController();
                $this->res->ko('general', $e->getMessage());
                $controller->index($this->res);
            }

        }
    }
}