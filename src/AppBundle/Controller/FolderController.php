<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Folder;
use AppBundle\Entity\Item;
use AppBundle\Form\FolderType;
use AppBundle\Form\ItemType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FolderController extends Controller
{
    /**
     * This lists all main Folders which are not hidden. Sorting by name.
     * @Route("/manager", name ="folders_list")
     * @Template
     */
    public function folderListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false], ['name' => 'ASC']);
        return [
            'folders' => $folders
        ];
    }

    /**
     * This lists all main Folders which are hidden. Sorting by name.
     * @Route("/hidden_list", name ="hiddenFolders")
     * @Template
     */
    public function hiddenFolderListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['hidden' => true], ['name' => 'ASC']);
        return [
            'folders' => $folders
        ];
    }

    /**
     * Create a new Folder according to FolderType Class.
     *
     * @Route("/manager/create_folder", name="createFolder")
     * @Route("/manager/edit_folder/{id}", name="editFolder", requirements={"id": "\d+"})
     * @Template
     * @param Request $request
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function folderCreateAction(Request $request, $id = null)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $folder = $em->getRepository('AppBundle:Folder')->find($id);
        } else {
            $folder = new Folder();
        }

        $form = $this->createForm(FolderType::class, $folder, [
            'user' => $this->getUser()->getGroups()->getValues(),
            'parent' => [],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($folder);
            $em->flush();
            return $this->redirectToRoute('folders_list');
        }

        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false]);

        return $this->render('@App/Create/folderCreate.html.twig', [
            'form' => $form->createView(),
            'folders' => $folders
        ]);
    }

    /**
     * This creates a new subfolder.
     *
     * Matches /manager/createFolder/*
     *
     * @Route("/manager/createFolder/{id}", name ="createSubFolder",requirements={"id": "\d+"})
     * @Template
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function subfolderCreateAction(Request $request, $id)
    {
        $folder = new Folder();
        $em = $this->getDoctrine()->getManager();
        $parentfolder = $em->getRepository('AppBundle:Folder')->find($id);
        $folder->setParent($parentfolder);

        $form = $this->createForm(FolderType::class, $folder, [
            'user' => $this->getUser()->getGroups()->getValues(),
            'parent' => $parentfolder->getGroups()->getValues(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($folder);
            $em->flush();
            return $this->redirectToRoute('item', ['id' => $id]);
        }

        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('AppBundle:Folder')->findBy(['parent' => null, 'hidden' => false]);

        return $this->render('@App/Create/folderCreate.html.twig', [
            'form' => $form->createView(),
            'folders' => $folders
        ]);
    }

    /**
     * This deletes a folder (folderID). CSRF protection with (token)
     * @Route("/manager/{folderId}/delete/{token}", name ="deleteFolder" ,requirements={"itemId": "\d+"})
     * @param Request $request
     * @param $folderId
     * @param $token
     * @return Response
     */
    public function folderDeleteAction(Request $request, $folderId, $token)
    {
        if ($request->isXmlHttpRequest()) {
            if ($this->isCsrfTokenValid('token', $token)) {
                $em = $this->getDoctrine()->getManager();
                $item = $em->getRepository('AppBundle:Folder')->find($folderId);
                try {
                    $em->remove($item);
                    $em->flush();
                    return new Response($this->get('translator')->trans('deleted'));
                } catch (ForeignKeyConstraintViolationException $exception) {
                    return new Response($this->get('translator')->trans('not_deleted'));
                }
            }
        }
        throw new AccessDeniedException();
    }
}
