<?php

namespace App\Controller\Form;

use App\Controller\PostController;
use App\Entity\Post;
use App\Entity\Res;
use DateTime;

class FormPostEdit extends FormController
{
    protected Res $res;
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
     * Treats the user part of the form
     * @param int $post
     * @return Res
     */
    public function treatFormPost(Post $post): Res
    {
        // -------------------------------------------------------------------- Checks.
        // post-title : checks.
        $resCheckPostFieldText = $this->checkPostedText('post-edit', 'post-title', 3, 64);
        if ($resCheckPostFieldText->isErr() === true) {
            return $this->res->ko('post-edit', $resCheckPostFieldText->getMsg()['post-edit']);
        }

        // post-slug : checks.
        $resCheckPostFieldTextSlug = $this->checkPostedSlug('post-edit', 'post-slug', $post->getId());
        if ($resCheckPostFieldTextSlug->isErr() === true) {
            return $this->res->ko('post-edit', $resCheckPostFieldTextSlug->getMsg()['post-edit']);
        }

        // post-image (File) : checks.
        $resCheckPostedFileImg = $this->checkPostedFileImg(
            'post-edit',
            'post-image',
            $this->sGlob->getFiles('post-image'),
            $post->getFeatImgFile(),
            $this->getPostImgMaxSize()
        );
        if ($resCheckPostedFileImg->isErr() === true) {
            return $this->res->ko('post-edit', $resCheckPostedFileImg->getMsg()['post-edit']);
        }

        // post-content : checks.
        $resCheckPostFieldTextarea = $this->checkPostedText('post-edit', 'post-content', 3, 10000);
        if ($resCheckPostFieldTextarea->isErr() === true) {
            return $this->res->ko('post-edit', $resCheckPostFieldTextarea->getMsg()['post-edit']);
        }

        // -------------------------------------------------------------------- Treatment.
        // Post Feat Image file : treatment.
        if ($this->sGlob->getFiles('post-image')['error'] === 0) {
            $resTreatFile = $this->treatFile($this->sGlob->getFiles('post-image'), 'post-image');

            if ($resTreatFile->isErr() === true) {
                $this->res->ko('post-image', $resTreatFile->getMsg()['treat-file']);
            }

            $savedFile = $resTreatFile->getResult()['treat-file'];
            $post->setFeatImgFile($savedFile);
            $post->setFeatImgId($savedFile->getId());
        }

        // Post simple fields : treatment.
        $post->setTitle($this->sGlob->getPost('post-title'));
        $post->setSlug($this->sGlob->getPost('post-slug'));
        $post->setContent($this->sGlob->getPost('post-content'));
        $post->setStatus($this->sGlob->getPost('post-status'));
        $post->setLastUpdate(new DateTime());

        // Post update.
        if ($this->res->isErr() === false) {
            $resPostUpdate =  $this->postController->updatePost($post);
            if ($resPostUpdate->isErr() === true) {
                return $this->res->ko('post-edit', $resPostUpdate->getMsg()['post-update']);
            }
            $this->res->ok('post-edit', 'post-edit-ok', $post);
        }
        return $this->res;
    }

}