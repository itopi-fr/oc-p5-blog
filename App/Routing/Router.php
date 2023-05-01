<?php

namespace App\Routing;

use App\Controller\CommentController;
use App\Controller\ContactController;
use App\Controller\HomeController;
use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Controller\Owner\OwnerController;
use App\Controller\OwnerInfoController;
use App\Controller\PostController;
use App\Controller\UserController;
use App\Entity\Res;
use Exception;
use App\Sys\SuperGlobals;

class Router
{
    private MainController $mainCtr;


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
    private SuperGlobals $superGlobals;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mainCtr = new MainController();
        $this->res = new Res();
        $this->superGlobals = new SuperGlobals();
    }


    public function run(): void
    {

        try {
            // TODO: Revoir ce système de routing (.htaccess).

            // Extract URL parts.
            $this->urlParts =           explode('/', $this->superGlobals->getGet('p'));
            $this->pageBase =           (array_key_exists(0, $this->urlParts)) ? $this->urlParts[0] : 'home';
            $this->pageAction =         (array_key_exists(1, $this->urlParts)) ? $this->urlParts[1] : '';
            $this->pageActionParam =    (array_key_exists(2, $this->urlParts)) ? $this->urlParts[2] : '';

            // Owner info.
            $sessionOwnerInfo = $this->superGlobals->getSes('ownerinfo');

            if ((empty($sessionOwnerInfo) === true) || ($sessionOwnerInfo == null)) {
                $this->ownerInfoController = new OwnerInfoController();
                $ownerInfo = $this->ownerInfoController->getOwnerInfo();
                $this->superGlobals->setSes('ownerinfo', $ownerInfo);
            }

            // Route.
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

                case ('owner'):
                    $controller = new OwnerController();
                    $controller->index($this->pageAction, $this->pageActionParam);
                    break;

                case ('comment'):
                    $controller = new CommentController();
                    $controller->index($this->pageAction);
                    break;

                case ('contact'):
                    $controller = new ContactController();
                    $controller->index($this->pageAction);
                    break;

                default:
                    $this->res->ko('general', "general-ko-page-not-found");
                    $controller = new ErrorPageController();
                    $controller->index($this->res);
                    break;
            }
        } catch (Exception $e) {
            $controller = new ErrorPageController();
            $this->res->ko('general', 'general-ko-unknown', $e);
            $controller->index($this->res);

            if ($this->superGlobals->getEnv('MODE_DEV') === 'true') {
                $this->mainCtr->dump($e);
            }
        }
    }
}
