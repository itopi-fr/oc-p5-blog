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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
    }


    /**
     * Delete a post from the database provided its ID
     * Calls the PostController::deletePost() method
     *
     * @param int $postId
     * @return Res
     */
    public function treatDeletePost(int $postId): Res
    {
        $postController = new PostController();
        $resDeletePost = $postController->deletePost($postId);
        if ($resDeletePost->isErr() === true) {
            return $this->res->ko('post-delete', $resDeletePost->getMsg()['post-delete']);
        }
        return $this->res->ok('post-delete', 'post-delete-ok');
    }


}
