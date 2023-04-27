<?php

namespace App\Controller;

use App\Controller\Form\FormCommentCreate;
use App\Entity\Comment;
use App\Entity\Res;
use App\Model\CommentModel;
use App\Model\UserModel;
use DateTime;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentController extends MainController
{
    protected Res $res;

    protected array $comments;

    protected CommentModel $commentModel;

    protected Comment $commentSingle;

    private UserModel $userModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->commentModel = new CommentModel();
        $this->commentSingle = new Comment();
        $this->userModel = new UserModel();
    }


    /**
     * Treat different actions for comments.
     * @param string $pageAction
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(string $pageAction): void
    {
        // Create Comment.
        if ($pageAction === 'create' && empty($this->sGlob->getPost("submit-comment-create")) === false) {
            $formPostCreate = new FormCommentCreate();
            $resTreatFormComment = $formPostCreate->treatForm();
            $this->twigData['result'] = $resTreatFormComment;
            $this->redirectTo("/article/" . $this->sGlob->getPost('comment-create-post-slug'));
            $this->twig->display("pages/page_fo_post_single.twig", $this->twigData);
        }
    }


    /**
     * Get the x last comments for a given post.
     * Only validated comments (status = 'valid') are returned.
     * @param int $postId
     * @param int $max
     * @return array
     * @throws Exception
     */
    public function getValidPostComments(int $postId, int $max): array
    {
        $resPostComments = $this->commentModel->getThisPostComments($postId, $max);

        if ($resPostComments === null) {
            return [];
        }

        foreach ($resPostComments as $key => $comObj) {
            if ($comObj->status !== 'valid') {
                unset($resPostComments[$key]);
                continue;
            } else {
                $resPostComments[$key] = $this->hydrateCommentObject($comObj);
                // Get the author User object.
                $resPostComments[$key]->setAuthorUser(
                    $this->userModel->getUserById($resPostComments[$key]->getAuthorId())
                );
            }
        }
        return $resPostComments;
    }


    /**
     * Get the x last comments (any post, any status).
     * @param int $max
     * @return Res $res
     */
    public function getAllComments(int $max): Res
    {
        try {
            $resAllComments = $this->commentModel->getAllPostComments($max);
            $allComments = [];

            if ($resAllComments === null) {
                return $this->res->ok('comments-get-all', 'comments-get-all-ok-none', $resAllComments);
            }

            foreach ($resAllComments as $commentObj) {
                $allComments[] = $this->hydrateCommentObject($commentObj);
            }

            return $this->res->ok('comments-get-all', 'comments-get-all-ok', $allComments);
        } catch (Exception $e) {
            return $this->res->ko('comments-get-all', 'comments-get-all-ko', $e);
        }
    }


    /**
     * Create a comment. If the author is the owner, the comment is valid by default.
     * @param Comment $comment
     * @return Res
     */
    public function createComment(Comment $comment): Res
    {
        $resCreateComment = $this->commentModel->createComment($comment);
        if ($resCreateComment === null) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-not-created');
        }
        return $this->res->ok('form-comment-create', 'form-comment-create-ok');
    }


    /**
     * Validate a comment by setting its status to 'valid'.
     * @param int $comId
     * @return Res
     */
    public function validateComment(int $comId): Res
    {
        if (empty($comId) === true) {
            return $this->res->ko('comment-validate', 'comment-validate-ko-no-id');
        }

        if ($this->commentModel->commentExistsById($comId) === false) {
            return $this->res->ko('comment-validate', 'comment-validate-ko-not-exists');
        }

        $resValidateComment = $this->commentModel->validateComment($comId);
        if ($resValidateComment < 1) {
            return $this->res->ko('comment-validate', 'comment-validate-ko-not-updated');
        }
        return $this->res->ok('comment-validate', 'comment-validate-ok');
    }


    /**
     * Delete a comment by setting its status to 'valid'.
     * @param int $comId
     * @return Res
     */
    public function deleteComment(int $comId): Res
    {
        if (empty($comId) === true) {
            return $this->res->ko('comment-delete', 'comment-delete-ko-no-id');
        }

        if ($this->commentModel->commentExistsById($comId) === false) {
            return $this->res->ko('comment-delete', 'comment-delete-ko-not-exists');
        }

        $resDeleteComment = $this->commentModel->deleteComment($comId);
        if ($resDeleteComment === null) {
            return $this->res->ko('comment-delete', 'comment-delete-ko');
        }
        return $this->res->ok('comment-delete', 'comment-delete-ok');
    }


    /**
     * Hydrate a proper comment object with the given object.
     * @param object $comObj
     * @return Comment
     * @throws Exception
     */
    public function hydrateCommentObject(object $comObj): Comment
    {
        $comment = new Comment();
        $comment->setComId($comObj->com_id);
        $comment->setPostId($comObj->post_id);
        $comment->setAuthorId($comObj->author_id);
        $comment->setContent($comObj->content);
        $comment->setCreatedDate(new DateTime($comObj->created_date));
        $comment->setLastUpdate(new DateTime($comObj->last_update));
        $comment->setStatus($comObj->status);

        // User object.
        if (empty($comObj->author_user) === true) {
            $comment->setAuthorUser($this->userModel->getUserById($comObj->author_id));
        } else {
            $comment->setAuthorUser($comObj->author_user);
        }
        return $comment;
    }


}
