<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reservationhote controller.
 *
 */
class ReservationHotesController extends Controller
{
    /**
     * Lists all reservationHote entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservationHotes = $em->getRepository('HotesBundle:ReservationHotes')->findAll();

        return $this->render('reservationhotes/index.html.twig', array(
            'reservationHotes' => $reservationHotes,
        ));
    }

    /**
     * Creates a new reservationHote entity.
     *
     */
    public function newAction(Request $request)
    {
        $reservationHote = new Reservationhote();
        $form = $this->createForm('HotesBundle\Form\ReservationHotesType', $reservationHote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservationHote);
            $em->flush();

            return $this->redirectToRoute('reservationhotes_show', array('numero_reservation' => $reservationHote->getNumero_reservation()));
        }

        return $this->render('reservationhotes/new.html.twig', array(
            'reservationHote' => $reservationHote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reservationHote entity.
     *
     */
    public function showAction(ReservationHotes $reservationHote)
    {
        $deleteForm = $this->createDeleteForm($reservationHote);

        return $this->render('reservationhotes/show.html.twig', array(
            'reservationHote' => $reservationHote,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reservationHote entity.
     *
     */
    public function editAction(Request $request, ReservationHotes $reservationHote)
    {
        $deleteForm = $this->createDeleteForm($reservationHote);
        $editForm = $this->createForm('HotesBundle\Form\ReservationHotesType', $reservationHote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservationhotes_edit', array('numero_reservation' => $reservationHote->getNumero_reservation()));
        }

        return $this->render('reservationhotes/edit.html.twig', array(
            'reservationHote' => $reservationHote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reservationHote entity.
     *
     */
    public function deleteAction(Request $request, ReservationHotes $reservationHote)
    {
        $form = $this->createDeleteForm($reservationHote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reservationHote);
            $em->flush();
        }

        return $this->redirectToRoute('reservationhotes_index');
    }

    /**
     * Creates a form to delete a reservationHote entity.
     *
     * @param ReservationHotes $reservationHote The reservationHote entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ReservationHotes $reservationHote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reservationhotes_delete', array('numero_reservation' => $reservationHote->getNumero_reservation())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
