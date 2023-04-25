<?php

namespace App\Controller\Form;

use App\Entity\Res;
use App\Model\PostModel;

class FormPostArchive extends FormController
{
    protected Res $res;
    protected PostModel $postModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
        $this->postModel = new PostModel();
    }


    /**
     * Archive a post from the database provided its ID
     * Calls the PostModel::archivePost() method
     * @param int $postId
     * @return Res
     */
    public function treatForm(int $postId): Res
    {
        $resArchivePost = $this->postModel->archivePost($postId);
        if ($resArchivePost === null) {
            return $this->res->ko('post-archive', 'post-archive-ko');
        }
        return $this->res->ok('post-archive', 'post-archive-ok');
    }
}
