<?php

namespace App\Controller\Owner;

use App\Controller\CommentController;

class OwnerCommentController extends OwnerController
{
    public function manageComments(): void
    {
        $this->twigData['title'] = 'Administration des commentaires';
        echo  $this->twig->render("pages/owner/page_bo_comments_manage.twig", $this->twigData);
    }

}