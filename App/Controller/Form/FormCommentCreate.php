<?php

namespace App\Controller\Form;

use App\Controller\CommentController;
use App\Controller\PostController;
use App\Entity\Comment;
use App\Entity\Res;
use App\Model\CommentModel;
use App\Model\PostModel;
use DateTime;

class FormCommentCreate extends FormController
{
    protected Res $res;

    protected CommentModel $commentModel;

    protected Comment $comment;

    private CommentController $commentController;

    private PostModel $postModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->commentModel = new CommentModel();
        $this->commentController = new CommentController();
        $this->comment = new Comment();
        $this->postModel = new PostModel();
    }


    /**
     * Treat the form. If ok, create the comment.
     * If the user is the owner of the post, the comment is automatically validated.
     * @return Res
     */
    public function treatForm(): Res
    {
        // -------------------------------------------------------------------- Checks.
        // User is connected.
        if (empty($this->sGlob->getSes('userobj')) === true) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-user-not-connected');
        }

        // Post id is set.
        $postId = $this->sGlob->getPost('comment-create-post-id');
        if (empty($postId) === true) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-post-id');
        }

        // Post exists.
        if ($this->postModel->postExistsById($postId) === false) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-post-not-exists');
        }

        // Post slug is set.
        $postSlug = $this->sGlob->getPost('comment-create-post-slug');
        if (empty($postSlug) === true) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-post-slug');
        }

        // Post exists by slug.
        if ($this->postModel->postExistsBySlug($postSlug) === false) {
            return $this->res->ko('form-comment-create', 'form-comment-create-ko-post-not-exists');
        }

        // Comment-content : checks.
        $resCheckComment = $this->checkPostedText(
            'form-comment-create',
            'comment-create-content',
            3,
            1024
        );
        if ($resCheckComment->isErr() === true) {
            return $this->res->ko('form-comment-create', $resCheckComment->getMsg()['form-comment-create']);
        }

        // -------------------------------------------------------------------- Create the comment object.
        $this->comment->setContent($this->sGlob->getPost('comment-create-content'));
        $this->comment->setAuthorId($this->sGlob->getSes('usrid'));
        $this->comment->setPostId($postId);
        $this->comment->setCreatedDate(new DateTime());

        // Status
        if ($this->sGlob->getSes('userobj')->getRole() === 'owner') {
            $this->comment->setStatus('valid');
        } else {
            $this->comment->setStatus('wait');
        }


        // -------------------------------------------------------------------- Insert the comment.

        $resCreateComment = $this->commentController->createComment($this->comment);
        if ($resCreateComment->isErr() === true) {
            return $this->res->ko('form-comment-create', $resCreateComment->getMsg()['form-comment-create']);
        }
        return $this->res->ok('form-comment-create', $resCreateComment->getMsg()['form-comment-create']);
    }

}