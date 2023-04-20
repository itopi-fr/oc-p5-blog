<?php

namespace App\Controller\Owner;

use App\Controller\CommentController;
use App\Controller\Form\FormPostCreate;
use App\Controller\Form\FormPostDelete;
use App\Controller\Form\FormPostEdit;
use App\Controller\PostController;
use App\Entity\Post;
use App\Entity\Res;
use App\Model\PostModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class OwnerPostController extends OwnerController
{
    protected array $posts;
    protected PostController $postController;
    protected FormPostCreate $formPostCreate;
    protected FormPostEdit $formPostEdit;
    protected FormPostDelete $formPostDelete;
    protected PostModel $postModel;
    protected Post $postSingle;
    protected Res $res;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postController = new PostController();
        $this->postModel = new PostModel();
        $this->postSingle = new Post();
        $this->formPostCreate = new FormPostCreate();
        $this->formPostEdit = new FormPostEdit();
        $this->formPostDelete = new FormPostDelete();
        $this->res = new Res();
    }


    /**
     * Used to display the list of posts in the BO (/owner/posts)
     * @return void
     */
    public function managePosts(): void
    {
        // TODO: Pagination
        $this->twigData['title'] = 'Administration des articles';
        $posts = $this->postModel->getLastPosts(100);
        foreach ($posts as $key => $post) {
            $posts[$key] = $this->postController->hydratePostObject($post);
        }
        $this->twigData['posts'] = $posts;

        // Display
        echo  $this->twig->render("pages/owner/page_bo_posts_manage.twig", $this->twigData);
    }


    /**
     * Create a post in the database
     * @return void
     */
    public function createPost(): void
    {
        $this->twigData['title'] = "Création d'un article";

        // Form Post Create sent => Treat it
        if (isset($_POST["submit-post-create"]) === true) {
            $this->twigData['result'] = $this->formPostCreate->treatForm();
        }

        // Display
        echo  $this->twig->render("pages/owner/page_bo_post_create.twig", $this->twigData);
    }


    /**
     * Used to display the edit form of a post in the BO (/owner/posts/edit/{postId})
     * @param int $postId
     * @return void
     */
    public function editPost(int $postId): void
    {
        $this->twigData['title'] = "Édition d'un article";

        // Get Post
        $resPost = $this->getPostById($postId);
        if ($resPost->isErr() === true) {
            $this->res->ko('post-edit', $resPost->getMsg()['post-get']);
            $this->twigData['result'] = $this->res;
        }

        // Form Post Edit sent => Treat it
        if (isset($_POST["submit-post-edit"]) === true) {
            $this->twigData['result'] = $this->formPostEdit->treatFormPost($resPost->getResult()['post-get']);
        }

        // Display
        $this->twigData['post'] = $resPost->getResult()['post-get'];
        echo  $this->twig->render("pages/owner/page_bo_post_edit.twig", $this->twigData);
    }


    /**
     * Delete a post from the database providing its ID
     * @param int $postId
     * @return void
     */
    public function deletePost(int $postId): void
    {
        // Get Post
        $resPost = $this->getPostById($postId);
        if ($resPost->isErr() === true) {
            $this->res->ko('post-delete', $resPost->getMsg()['post-get']);
            $this->twigData['result'] = $this->res;
        }

        // Delete Post
        $this->twigData['result'] = $this->formPostDelete->treatDeletePost($resPost->getResult()['post-get']->getId());

        // Display
        $this->managePosts();
    }


    /**
     * Get a Post by its slug
     * @param $postSlug
     * @return Res
     */
    public function getPostBySlug($postSlug): Res
    {
        // Check that the post exists
        if ($this->postModel->postExistsBySlug($postSlug) === false) {
            $this->res->ko('post-get', 'post-get-ko-not-exists');
            return $this->res;
        }

        // Build the post
        $postObject = $this->postModel->getPostBySlug($postSlug);
        $post = $this->postController->hydratePostObject($postObject);

        // Return the post
        if ($post->getId() > 0) {
            $this->res->ok('post-get', 'post-get-ok-get-post', $post);
        } else {
            $this->res->ko('post-get', 'post-get-ko-get-post');
        }
        return $this->res;
    }


    /**
     * Get a Post class object by its slug
     * @param $postId
     * @return Res
     */
    public function getPostById($postId): Res
    {
        // Check that the post exists
        if ($this->postModel->postExistsById($postId) === false) {
            $this->res->ko('post-get', 'post-get-ko-not-exists');
            return $this->res;
        }

        // Build the post
        $postObject = $this->postModel->getPostById($postId);
        if (is_null($postObject) === true) {
            $this->res->ko('post-get', 'post-get-ko-not-exists');
            return $this->res;
        }
        $post = $this->postController->hydratePostObject($postObject);

        // Return the post
        $this->res->ok('post-get', 'post-get-ok-get-post', $post);

        return $this->res;
    }
}
