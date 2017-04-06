<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Folder;
use AppBundle\Entity\Item;
use AppBundle\Entity\Log;
use AppBundle\Form\FolderType;
use AppBundle\Form\ItemType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ItemController extends Controller
{
    /**
     * Lists items in a specific folder (id)
     * @Route("/manager/{id}", name ="item",requirements={"id": "\d+"})
     * @Template
     * @param $id
     * @return array
     */
    public function itemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $folder = $em->getRepository('AppBundle:Folder')->find($id);
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false], ['name' => 'ASC']);
        return [
            'folder' => $folder,
            'folders' => $folders
        ];
    }

    /**
     * Lists logs for an item.
     * @Route("/manager/item/log/{id}", name ="logs")
     * @Template
     * @param $id
     * @return array
     */
    public function logsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($id);
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false], ['name' => 'ASC']);
        return [
            'item' => $item,
            'folder' => $item->getFolder(),
            'folders' => $folders
        ];
    }

    /**
     * Retrieve a password an update logs.
     * @Route("/manager/item/{id}", name ="item_detail",requirements={"id": "\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function retrievePasswordAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $item = $em->getRepository('AppBundle:Item')->find($id);

            $log = new Log();
            $log->setUser($this->getUser());
            $log->setIp($request->getClientIp());
            $log->setItem($item);
            $log->setTimestamp(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();
            return new Response($item->getValue());

        } else {
            throw new AccessDeniedException();
        }
    }

    /**
     * This creates an item.
     * @Route("/manager/create/{folderId}", name ="createItem",requirements={"folderId": "\d+"})
     * @Route("/manager/{folderId}/edit/{itemId}", name ="editItem")
     * @Template
     * @param Request $request
     * @param null $folderId
     * @param null $itemId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function itemCreateAction(Request $request, $folderId = null, $itemId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $folder = $em->getRepository('AppBundle:Folder')->find($folderId);
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false]);

        if ($itemId) {
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository('AppBundle:Item')->find($itemId);
        } else {
            $item = new Item();
            $item->setFolder($folder);
        }

        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            if ($folder->getParent() != null) {
                $redirectFolder = $folder->getParent()->getId();
            } else {
                $redirectFolder = $folderId;
            }

            return $this->redirectToRoute('item', ['id' => $redirectFolder]);
        }

        return $this->render('@App/Create/itemCreate.html.twig', [
            'folder' => $folder,
            'folders' => $folders,
            'form' => $form->createView()
        ]);
    }

    /**
     * This deletes an item.
     * @Route("/manager/{folderId}/delete/{itemId}/{token}", name ="deleteItem" ,requirements={"itemId": "\d+"})
     * @param Request $request
     * @param $itemId
     * @param $token
     * @return Response
     */
    public function itemDeleteAction(Request $request, $itemId, $token)
    {
        if ($request->isXmlHttpRequest()) {
            if ($this->isCsrfTokenValid('token', $token)) {
                $em = $this->getDoctrine()->getManager();
                $item = $em->getRepository('AppBundle:Item')->find($itemId);
                $em->remove($item);
                $em->flush();
                return new Response($this->get('translator')->trans('deleted'));
            }
        }
        throw new AccessDeniedException();
    }
}