<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Res;
use App\Model\CommentModel;
use App\Model\PostModel;
use DateTime;
use DateTimeZone;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class PostController - Post functions.
 */
class PostController extends MainController
{
    /**
     * @var Res
     */
    protected Res $res;

    /**
     * @var array
     */
    protected array $posts;

    /**
     * @var PostModel
     */
    protected PostModel $postModel;

    /**
     * @var Post
     */
    protected Post $postSingle;

    /**
     * @var CommentModel
     */
    protected CommentModel $commentModel;

    /**
     * @var FileController
     */
    private FileController $fileController;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->fileController = new FileController();
        $this->postSingle = new Post();
    }


    /**
     * Display the post list page with comments.
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function index(): void
    {
        $resPosts = $this->postModel->getLastPubPosts(25);

        if ($resPosts === null) {
            $this->twigData['posts'] = null;
            $this->twig->display("pages/page_fo_posts.twig", $this->twigData);
            return;
        } else {
            $this->posts = $resPosts;
        }

        // Build each post object.
        foreach ($this->posts as $key => $post) {
            $this->posts[$key] = $this->hydratePostObject($post);
        }

        // Display.
        $this->twigData['posts'] = $this->posts;
        $this->twig->display("pages/page_fo_posts.twig", $this->twigData);
    }


    /**
     * Create a post in the database providing a Post object
     *
     * @param Post $post - The Post object to create.
     * @return Res
     */
    public function createPost(Post $post): Res
    {
        // Generate slug
        $slug = $this->generateSlug($post->getTitle());
        if ($this->postModel->postExistsBySlug($slug) === true) {
            $slug = $slug . '-' . $this->generateKey(3);
        }
        $post->setSlug($slug);

        // TODO: Random image if empty.

        $resCreatePost = $this->postModel->createPost($post);
        if ($resCreatePost === null) {
            return $this->res->ko('post-create', 'post-create-ko');
        }
        return $this->res->ok('post-create', 'post-create-ok');
    }


    /**
     * Update a post in the database providing a Post object
     * Calls the PostModel::updatePost() method
     *
     * @param Post $post - The Post object to update.
     * @return Res
     */
    public function updatePost(Post $post): Res
    {
        // Remove the old featured image if a new one has been sent.
        $dbFeatImgId = $this->getPostById($post->getPostId())->getFeatImgId();
        if ($dbFeatImgId !== $post->getFeatImgId()) {
            $this->fileController->deleteFileById($dbFeatImgId);
        }

        // Update the post.
        $resUpdate = $this->postModel->updatePost($post);
        if ($resUpdate === null) {
            return $this->res->ko('post-update', 'post-update-ko');
        }
        return $this->res->ok('post-update', 'post-update-ok');
    }


    /**
     * Simply change the status of a post to "arch" (archived)
     *
     * @param int $postId - The ID of the post to archive.
     * @return Res
     */
    public function archivePost(int $postId): Res
    {
        $resArchivePost = $this->postModel->archivePost($postId);
        if ($resArchivePost === null) {
            return $this->res->ko('post-archive', 'post-archive-ko');
        }
        return $this->res->ok('post-archive', 'post-archive-ok');
    }


    /**
     * Delete a post from the database providing its ID
     * Also delete the comments and the featured image associated to this post
     * Calls the PostModel::deletePost() method
     *
     * @param int $postId - The ID of the post to delete.
     * @return Res
     */
    public function deletePost(int $postId): Res
    {
        // Verify that the post exists
        if ($this->postModel->postExistsById($postId) === false) {
            return $this->res->ko('post-delete', 'post-delete-ko-not-exists');
        }
        $post = $this->getPostById($postId);

        // Delete associated comments
        $resDeletePostComments = $this->commentModel->deletePostComments($postId);
        if ($resDeletePostComments === null) {
            return $this->res->ko('post-delete', 'post-delete-comments-ko');
        }

        // Delete associated featured image
        $fileController = new FileController();
        $resDeletePostFeaturedImage = $fileController->deleteFileById($post->getFeatImgId());
        if ($resDeletePostFeaturedImage->isErr() === true) {
            return $this->res->ko('post-delete', 'post-delete-featured-image-ko');
        }

        // Delete the post
        $resDeletePost = $this->postModel->deletePost($postId);
        if ($resDeletePost === null) {
            return $this->res->ko('post-delete', 'post-delete-ko');
        }
        return $this->res->ok('post-delete', 'post-delete-ok');
    }


    /**
     * Get a post from the database providing its slug. Also get the comments associated to this post.
     *
     * @param string $postSlug - The slug of the post to get.
     * @return void
     */
    public function single(string $postSlug): void
    {
        // Check if the post exists.
        if ($this->postModel->postExistsBySlug($postSlug) === false) {
            $this->res->ko('post-single', "post-single-ko-not-exists");
            $this->twig->display("pages/page_fo_error.twig", $this->twigData);
            return;
        }

        // Build the Post object.
        $postObject = $this->postModel->getPostBySlug($postSlug);
        $this->postSingle = $this->hydratePostObject($postObject);

        // Get the comments.
        $commentController = new CommentController();
        $this->postSingle->setComments($commentController->getValidPostComments($this->postSingle->getPostId(), 100));

        // Display.
        $this->twigData['post'] = $this->postSingle;
        $this->twig->display("pages/page_fo_post_single.twig", $this->twigData);
    }


    /**
     * Get a post from the database providing its ID
     *
     * @param int $postId - The ID of the post to get.
     * @return Post
     */
    public function getPostById(int $postId): Post
    {
        $postObject = $this->postModel->getPostById($postId);
        return $this->hydratePostObject($postObject);
    }


    /**
     * Get x last published posts from the database
     *
     * @param int $nbPosts - The number of posts to get.
     * @return array
     * @throws Exception
     */
    public function getLastPubPosts(int $nbPosts): array
    {
        $resPosts = $this->postModel->getLastPubPosts($nbPosts);

        if ($resPosts === null) {
            return [];
        } else {
            $this->posts = $resPosts;
        }

        // Build each post object.
        foreach ($this->posts as $key => $post) {
            $this->posts[$key] = $this->hydratePostObject($post);

            // Convert created_date and last_update to GMT+2.
            $this->posts[$key]->setCreationDate(
                $this->posts[$key]->getCreationDate()->setTimezone(new DateTimeZone('Europe/Paris'))
            );

            $this->posts[$key]->setLastUpdate(
                $this->posts[$key]->getLastUpdate()->setTimezone(new DateTimeZone('Europe/Paris'))
            );
        }
        return $this->posts;
    }


    /**
     * Generate a slug from a string
     *
     * @param string $postTitle - The string to convert to slug.
     * @return string
     */
    public function generateSlug(string $postTitle): string
    {
        $slug = strtolower($postTitle);
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        return $slug;
    }


    /**
     * Hydrate the Post class object with the data from a post standard object
     *
     * @param object $postObject - The post standard object to hydrate the Post class object with.
     * @return Post
     * @throws Exception
     */
    public function hydratePostObject(object $postObject): Post
    {
        $fileController = new FileController();
        $post = new Post();

        $post->setPostId($postObject->post_id);
        $post->setAuthorId($postObject->author_id);
        $post->setFeatImgId($postObject->feat_img_id);
        $post->setFeatImgFile($fileController->getFileById($postObject->feat_img_id));
        $post->setTitle($postObject->title);
        $post->setSlug($postObject->slug);
        $post->setExcerpt($postObject->excerpt);
        $post->setContent($postObject->content);
        $post->setCreationDate(new DateTime($postObject->creation_date));
        $post->setLastUpdate(new DateTime($postObject->last_update));
        $post->setStatus($postObject->status);
        return $post;
    }


}
