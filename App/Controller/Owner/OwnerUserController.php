<?php

namespace App\Controller\Owner;

class OwnerUserController extends OwnerController
{
    public function manageUsers(): void
    {
        $this->twigData['title'] = 'Administration des utilisateurs';
        echo  $this->twig->render("pages/owner/page_bo_users_manage.twig", $this->twigData);
    }
}