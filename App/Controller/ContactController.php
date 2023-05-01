<?php

namespace App\Controller;

use App\Controller\Form\FormContactOwner;
use App\Entity\Res;

/**
 * Controller for the contact features.
 * Mostly used to redirect to the right form controller.
 */
class ContactController extends MainController
{
    /**
     * @var Res
     */
    protected Res $res;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->res = new Res();
    }


    /**
     * @param string $action
     * @return void
     */
    public function index(string $action): void
    {
        // Verify action.
        // If new actions, add them here. ("&& $action !== 'new-action'").
        if ($action !== 'owner') {
            $this->twigData['result'] = $this->res->ko("contact", "contact-ko-action");
            $this->twig->display("pages/page_fo_error.twig", $this->twigData);
        }

        // Contact Owner Form sent => Treat it.
        if (empty($this->sGlob->getPost("submit-contact-owner")) === false) {
            $this->twigData['result'] = (new FormContactOwner())->treatForm();
            $this->twigData['formsent'] = true;
            $this->redirectTo('/', 5);
        }
        $this->twig->display("pages/page_fo_home.twig", $this->twigData);
    }


}
