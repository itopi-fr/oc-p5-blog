<?php

namespace App\Controller\Owner;

class OwnerUserController extends OwnerController
{
    public function manageUsers(): void
    {
        $this->twigData['title'] = 'Administration des utilisateurs';
        $this->twig->display("pages/owner/page_bo_users_manage.twig", $this->twigData);
    }
}
