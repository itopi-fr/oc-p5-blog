<?php

namespace App\Controller\Owner;

use App\Controller\CommentController;
use App\Controller\PostController;
use App\Model\CommentModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class OwnerCommentController - Manage the comments (owner panel).
 */
class OwnerCommentController extends OwnerController
{
    /**
     * @var CommentController
     */
    protected CommentController $commentController;

    /**
     * @var CommentModel
     */
    protected CommentModel $commentModel;

    /**
     * @var PostController
     */
    protected PostController $postController;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->commentController = new CommentController();
        $this->commentModel = new CommentModel();
        $this->postController = new PostController();
    }


    /**
     * Manage comments.
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function manageComments(): void
    {
        // TODO: Pagination.
        $this->twigData['title'] = 'Administration des commentaires';

        // Get all comments.
        $allComments = [];
        $resGetComments = $this->commentController->getAllPostComments(100);

        if ($resGetComments->isErr() === true) {
            $this->twigData['comments'] = null;
            $this->twig->display("pages/owner/page_fo_error.twig", $this->twigData);
            return;
        }

        $allComments = $resGetComments->getResult()['comments-get-all'];

        // Display.
        $this->twigData['comments'] = $allComments;
        $this->twig->display("pages/owner/page_bo_comments_manage.twig", $this->twigData);
    }


    /**
     * Validate a comment (set the status to 'valid')
     *
     * @param string $comIdFromUrl - The comment ID from the URL
     * @return void
     */
    public function validateComment(string $comIdFromUrl): void
    {
        $comId = (int) $comIdFromUrl;
        $this->commentController->validateComment($comId);
        $this->redirectTo("/owner/comments", 0);
    }


    /**
     * Delete a comment
     *
     * @param string $comIdFromUrl - The comment ID from the URL
     * @return void
     */
    public function deleteComment(string $comIdFromUrl): void
    {
        $comId = (int) $comIdFromUrl;
        $this->commentController->deleteComment($comId);
        $this->redirectTo("/owner/comments", 0);
    }


}
