<?php

namespace App\Controller\Owner;

use App\Controller\CommentController;

class OwnerCommentController extends OwnerController
{
    public function manageComments(): void
    {
        $this->twigData['title'] = 'Administration des commentaires';
        $this->twig->display("pages/owner/page_bo_comments_manage.twig", $this->twigData);
    }

}