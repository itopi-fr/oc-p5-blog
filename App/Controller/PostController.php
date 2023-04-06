<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Post;
use App\Model\PostModel;
use DateTime;

class PostController extends MainController
{
    protected array $posts;
    protected PostModel $postModel;
    protected Post $postSingle;
    protected FileController $fileController;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
    }

    public function index()
    {
        $this->posts = $this->getLastPubPosts(10);

        // hydrate each post
        foreach ($this->posts as $key => $post) {
            $this->posts[$key] = $this->hydratePostObject($post);
        }

        $this->twigData['posts'] = $this->posts;
        echo  $this->twig->render("pages/page_fo_posts.twig", $this->twigData);
    }

    public function getLastPubPosts(): array|null
    {
        return $this->postModel->getLastPubPosts(3);
    }

    public function single($postSlug)
    {
        $this->postSingle = new Post();

        if (!$this->postModel->postExistsBySlug($postSlug)) {
            $this->twigData['error'] = ['code' => 404,
                                        'title' => 'erreur article',
                                        'message' => "L'article n'existe pas"];
            echo  $this->twig->render("pages/page_fo_error.twig", $this->twigData);
            return;
        }

        $postObject = $this->postModel->getPostBySlug($postSlug);
        $this->postSingle = $this->hydratePostObject($postObject);

        $this->twigData['post'] = $this->postSingle;
        echo  $this->twig->render("pages/page_fo_post_single.twig", $this->twigData);
    }

    /**
     * Hydrate the Post class object with the data from a post standard object
     *
     * @param object $postObject
     * @return void
     */
    private function hydratePostObject($postObject): Post
    {
        $fileController = new FileController();
        $post = new Post();

        $post->setId($postObject->id);
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