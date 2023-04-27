<?php

namespace App\Controller\Owner;

use App\Controller\ErrorPageController;
use App\Controller\MainController;
use App\Entity\Res;
use App\Model\UserModel;

/**
 * Class OwnerController - Manage the owner pages (posts, comments, users).
 * This class is herited by more specific owner controllers.
 */
class OwnerController extends MainController
{
    /**
     * @var Res
     */
    private Res $res;

    /**
     * @var UserModel
     */
    private UserModel $userModel;

    /**
     *
     */
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
        // Check if the user is an owner, if not, redirect to the error page.
        if ($this->isOwner() === false) {
            $this->res->ko('general', "La page demandée n'existe pas");
            $controller = new ErrorPageController();
            $controller->index($this->res);
            return;
        }

        // TODO : add a check to $pageActionParam.

        if ($pageAction === 'posts') {
            $controller = new OwnerPostController();
            $controller->managePosts();
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
        } elseif ($pageAction === 'comment-validate') {
            $controller = new OwnerCommentController();
            $controller->validateComment($pageActionParam);
        } elseif ($pageAction === 'comment-delete') {
            $controller = new OwnerCommentController();
            $controller->deleteComment($pageActionParam);
        } elseif ($pageAction === 'users') {
            $controller = new OwnerUserController();
            $controller->manageUsers();
        } else {
            $this->twigData['title'] = 'Administration - Accueil';
            $this->twig->display("pages/owner/page_bo_owner.twig", $this->twigData);
        }
    }

    /**
     * Check if the user is an owner
     * @return bool
     */
    public function isOwner(): bool
    {
        $sessUserObj = $this->sGlob->getSes('userobj');

        if (empty($sessUserObj) === true) {
            return false;
        }
        if ($sessUserObj === null) {
            return false;
        }
        // Verify that the user exists.
        if ($this->userModel->userExistsByEmailPassword($sessUserObj->getEmail(), $sessUserObj->getPass()) === false) {
            return false;
        } else {
            // Verify that the user is an owner.
            $user = $this->userModel->getUserByEmail($sessUserObj->getEmail());
            if ($user->getRole() === 'owner') {
                return true;
            } else {
                return false;
            }
        }
    }
}
