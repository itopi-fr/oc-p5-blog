<?php

namespace App\Controller\Form;

use App\Entity\File;
use App\Entity\Post;
use App\Entity\Res;
use App\Model\PostModel;
use DateTime;

class FormPostCreate extends FormController
{
    protected Res $res;
    protected PostModel $postModel;
    protected Post $post;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->postModel = new PostModel();
        $this->post = new Post();
    }


    /**
     * Used to create a new post
     * @return Res
     */
    public function treatForm(): Res
    {
        // -------------------------------------------------------------------- Checks
        // post-title : checks
//        $resCheckPostFieldText = $this->checkPostedText('post-create', 'post-title', 3, 64);
        $resCheckPostFieldText = $this->checkPostedText(
            'post-create',
            'post-title',
            3,
            64
        );
        if ($resCheckPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldText->getMsg()['post-create']);
        }

        // post-slug : checks
        $resCheckPostFieldTextSlug = $this->checkPostedSlug('post-create', 'post-slug', -1);
        if ($resCheckPostFieldTextSlug->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldTextSlug->getMsg()['post-create']);
        }

        // post-image (File) : checks
        $resCheckPostedFileImg = $this->checkPostedFileImg(
            'post-create',
            'post-image',
            $this->sGlob->getFiles('post-image'),
            new File(),
            $this->getPostImgMaxSize()
        );
        if ($resCheckPostedFileImg->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostedFileImg->getMsg()['post-create']);
        }

        // post-content : checks
//        $resCheckPostFieldText = $this->checkPostedText('post-create', 'post-content', 3, 10000);
        $resCheckPostFieldText = $this->checkPostedText('post-create', 'post-content', 3, 10000);
        if ($resCheckPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldText->getMsg()['post-create']);
        }

        // post-status : checks
        $resCheckStatusPostFieldText = $this->checkPostedRadio(
            'post-create',
            'post-status',
            ['draft', 'pub', 'arch']
        );
        if ($resCheckStatusPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckStatusPostFieldText->getMsg()['post-create']);
        }

        // -------------------------------------------------------------------- Treatments
        $this->post->setAuthorId($this->sGlob->getSes('userid'));
        $this->post->setTitle($this->sGlob->getPost('post-title'));
        $this->post->setSlug($this->sGlob->getPost('post-slug'));
        $this->post->setContent($this->sGlob->getPost('post-content'));
        $this->post->setExcerpt(substr($this->sGlob->getPost('post-content'), 0, 80) . '...');
        $this->post->setStatus($this->sGlob->getPost('post-status'));
        $this->post->setCreationDate(new DateTime());
        $this->post->setLastUpdate(new DateTime());

        // Upload and save the image
        if ($this->sGlob->getFiles('post-image')['error'] === 0) {
            $resTreatFile = $this->treatFile($this->sGlob->getFiles('post-image'), 'post-image');

            if ($resTreatFile->isErr() === true) {
                $this->res->ko('post-create', $resTreatFile->getMsg()['treat-file']);
            }

            $savedFile = $resTreatFile->getResult()['treat-file'];
            $this->post->setFeatImgFile($savedFile);
            $this->post->setFeatImgId($savedFile->getId());
        }

        if (is_null($this->postModel->createPost($this->post)) === false) {
            $this->res->ok('post-create', 'post-create-ok', null);
        } else {
            $this->res->ko('post-create', 'post-create-ko');
        }
        return $this->res;
    }
}
