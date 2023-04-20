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


    public function treatForm(): Res
    {
        // -------------------------------------------------------------------- Checks
        // post-title : checks
        $resCheckPostFieldText = $this->checkPostFieldText('post-create', 'post-title', 3, 64);
        if ($resCheckPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldText->getMsg()['post-create']);
        }

        // post-slug : checks
        $resCheckPostFieldTextSlug = $this->checkPostFieldTextSlug('post-create', 'post-slug', -1);
        if ($resCheckPostFieldTextSlug->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldTextSlug->getMsg()['post-create']);
        }

        // post-image (File) : checks
        $resCheckPostedFileImg = $this->checkPostedFileImg(
            'post-create',
            'post-image',
            $_FILES['post-image'],
            new File(),
            $this->getPostImgMaxSize()
        );
        if ($resCheckPostedFileImg->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostedFileImg->getMsg()['post-create']);
        }

        // post-content : checks
        $resCheckPostFieldText = $this->checkPostFieldText('post-create', 'post-content', 3, 10000);
        if ($resCheckPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckPostFieldText->getMsg()['post-create']);
        }

        // post-status : checks
        $resCheckStatusPostFieldText = $this->checkPostRadio(
            'post-create',
            'post-status',
            ['draft', 'pub', 'arch']
        );
        if ($resCheckStatusPostFieldText->isErr() === true) {
            return $this->res->ko('post-create', $resCheckStatusPostFieldText->getMsg()['post-create']);
        }

        // -------------------------------------------------------------------- Treatments
        $this->post->setAuthorId($_SESSION['userid']);
        $this->post->setTitle($_POST['post-title']);
        $this->post->setSlug($_POST['post-slug']);
        $this->post->setContent($_POST['post-content']);
        $this->post->setExcerpt(substr($_POST['post-content'], 0, 80) . '...');
        $this->post->setStatus($_POST['post-status']);
        $this->post->setCreationDate(new DateTime());
        $this->post->setLastUpdate(new DateTime());

        // Upload and save the image
        if ($_FILES['post-image']['error'] === 0) {
            $resTreatFile = $this->treatFile($_FILES['post-image'], 'post-image');

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
