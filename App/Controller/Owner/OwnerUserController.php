<?php

namespace App\Controller\Owner;

/**
 * Class OwnerUserController - Manage the users in the BO (owner).
 */
class OwnerUserController extends OwnerController
{
    /**
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function manageUsers(): void
    {
        $this->twigData['title'] = 'Administration des utilisateurs';
        $this->twig->display("pages/owner/page_bo_users_manage.twig", $this->twigData);
    }


}
