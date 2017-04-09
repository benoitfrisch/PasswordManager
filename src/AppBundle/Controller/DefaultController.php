<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template
     */
    public function redirectAction(Request $request)
    {
        $language = $request->getPreferredLanguage($this->getParameter('languages'));
        return $this->redirect($this->generateUrl('folders_list', ['_locale' => $language]));
    }
}