<?php

namespace App\Controller\Form;

use App\Controller\PostController;
use App\Entity\Res;

/**
 * Class FormPostDelete - Manage the post deletion form (owner).
 */
class FormPostDelete extends FormController
{
    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var PostController
     */
    protected postController $postController;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->postController = new PostController();
    }


    /**
     * Delete a post from the database provided its ID
     * Calls the PostController::deletePost() method
     * @param int $postId
     * @return Res
     */
    public function treatDeletePost(int $postId): Res
    {
        $resDeletePost = $this->postController->deletePost($postId);
        if ($resDeletePost->isErr() === true) {
            return $this->res->ko('post-delete', $resDeletePost->getMsg()['post-delete']);
        }
        return $this->res->ok('post-delete', 'post-delete-ok');
    }
}
