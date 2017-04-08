<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Folder;
use AppBundle\Entity\Item;
use AppBundle\Entity\Log;
use AppBundle\Form\FolderType;
use AppBundle\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class DefaultController extends Controller
{
    public function redirectAction(Request $request)
    {
        $language = $request->getPreferredLanguage($this->getParameter('languages'));
        return $this->redirect($this->generateUrl('home', ['_locale' => $language]));
    }

    /**
     * @Route("/", name ="home")
     * @Template
     */
    public function mainAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY') == true) {
            return $this->forward('AppBundle:Folder:folderList');
        }
    }
}