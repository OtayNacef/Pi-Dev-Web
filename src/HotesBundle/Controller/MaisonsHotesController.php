<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\MaisonsHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Maisonshote controller.
 *
 */
class MaisonsHotesController extends Controller
{
    /**
     * Lists all maisonsHote entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u= $this->container->get('security.token_storage')->getToken()->getUser();

        $maisonsHotes = $em->getRepository('HotesBundle:MaisonsHotes')->findAll();
        $maisonsHote = new MaisonsHotes();
        $form = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $maisonsHote->setUser($u);
            $em->persist($maisonsHote);
            $em->flush();
            return $this->redirectToRoute('maisonshotes_index');
        }

        return $this->render('maisonshotes/index.html.twig', array(
            'maisonsHotes' => $maisonsHotes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new maisonsHote entity.
     *
     */
    public function newAction(Request $request)
    {
        $maisonsHote = new MaisonsHotes();
        $form = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($maisonsHote);
            $em->flush();

            return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
        }

        return $this->render('maisonshotes/new.html.twig', array(
            'maisonsHote' => $maisonsHote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a maisonsHote entity.
     *
     */
    public function showAction(Request $request,MaisonsHotes $maisonsHote)
    {  // $maisonsHote= new MaisonsHotes();
        $deleteForm = $this->createDeleteForm($maisonsHote);
        $editForm = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('maisonshotes_edit', array('id' => $maisonsHote->getId()));
        }

        return $this->render('maisonshotes/show.html.twig',
            array(
            'maisonsHote' => $maisonsHote,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing maisonsHote entity.
     *
     */
    public function editAction(Request $request, MaisonsHotes $maisonsHote)
    {
        $deleteForm = $this->createDeleteForm($maisonsHote);
        $editForm = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
        }

        return $this->render('maisonshotes/edit.html.twig', array(
            'maisonsHote' => $maisonsHote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a maisonsHote entity.
     *
     */
//    public function deleteAction(Request $request, MaisonsHotes $maisonsHote)
//    {
//        $form = $this->createDeleteForm($maisonsHote);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($maisonsHote);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('maisonshotes_index');
//    }
    function  deleteAction($id)
    {
        $am=$this->getDoctrine()->getManager();
        $hote=$am->getRepository("HotesBundle:MaisonsHotes")->find($id);
        $am->remove($hote);
        $am->flush();
        return $this->redirectToRoute("maisonshotes_index");

    }


    /**
     * Creates a form to delete a maisonsHote entity.
     *
     * @param MaisonsHotes $maisonsHote The maisonsHote entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MaisonsHotes $maisonsHote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('maisonshotes_delete', array('id' => $maisonsHote->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
