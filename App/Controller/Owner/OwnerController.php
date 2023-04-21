<?php

namespace App\Controller\Owner;

use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Entity\Res;
use App\Model\UserModel;

class OwnerController extends MainController
{
    private Res $res;
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->res = new Res();
    }

    /**
     * Index. Used to call or redirect to the right method or page
     * @return void
     */
    public function index(string $pageAction, string $pageActionParam): void
    {
        // Check if the user is an owner, if not, redirect to the error page
        if ($this->isOwner() === false) {
            $this->res->ko('general', "La page demandée n'existe pas");
            $controller = new ErrorPageController();
            $controller->index($this->res);
            return;
        }

        // TODO : add a check to $pageActionParam

        if ($pageAction === 'posts') {
            $controller = new OwnerPostController();
            $controller->managePosts();
            return;
        } elseif ($pageAction === 'post-create') {
            $controller = new OwnerPostController();
            $controller->createPost();
        } elseif ($pageAction === 'post-edit') {
            $controller = new OwnerPostController();
            $controller->editPost($pageActionParam);
        } elseif ($pageAction === 'post-delete') {
            $controller = new OwnerPostController();
            $controller->deletePost($pageActionParam);
        } elseif ($pageAction === 'post-archive') {
            $controller = new OwnerPostController();
            $controller->archivePost($pageActionParam);
        } elseif ($pageAction === 'comments') {
            $controller = new OwnerCommentController();
            $controller->manageComments();
        } elseif ($pageAction === 'users') {
            $controller = new OwnerUserController();
            $controller->manageUsers();
            return;
        } else {
            $this->twigData['title'] = 'Administration - Accueil';
            echo  $this->twig->render("pages/owner/page_bo_owner.twig", $this->twigData);
            return;
        }
    }

    /**
     * Check if the user is an owner
     * @param string $ownerEmail
     * @param string $ownerPassword
     * @return bool
     */
    public function isOwner(): bool
    {
        $sessUserObj = $this->sGlob->getSes('userobj');

        if (empty($sessUserObj) === true) {
            return false;
        }
        if (is_null($sessUserObj) === true) {
            return false;
        }
        // Verify that the user exists
        if ($this->userModel->userExistsByEmailPassword($sessUserObj->getEmail(), $sessUserObj->getPass()) === false) {
            return false;
        } else {
            // Verify that the user is an owner
            $user = $this->userModel->getUserByEmail($sessUserObj->getEmail());
            if ($user->getRole() === 'owner') {
                return true;
            } else {
                return false;
            }
        }
    }
}