<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\MaisonsHotes;
use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReservationController extends Controller
{
    /***************     Reservation      *************************/
    public function ReserverAction($id, Request $request)
    {
        $reservation = new ReservationHotes();
        $form = $this->createForm('HotesBundle\Form\ReservationHotesType', $reservation);
        $form->handleRequest($request);
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $am = $this->getDoctrine()->getManager();
        $maison = $am->getRepository("HotesBundle:MaisonsHotes")->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $d_deb = $form->get('date_debut')->getData();
            $d_fin = $form->get('date_fin')->getData();
            if ($d_deb > $d_fin) {
                return $this->redirectToRoute('erreur');

            }

            $nb_per = $form->get('nb_personne')->getData();
            $prix = $maison->getPrix();
            $diff = $d_fin->diff($d_deb)->format("%a");

            $reservation->setPrix($prix * $diff * $nb_per);
            $reservation->setMaisonsHotes($maison);
            $reservation->setUser($u);
            $am->persist($reservation);
            $am->flush();


            return $this->redirectToRoute('afficherReservation', array('num' => $reservation->getNumeroReservation()));
        }
        return $this->render('@Hotes\hotes\Reservation.html.twig', array(
            'reservation' => $reservation,
            'form' => $form->createView(),
            'maison' => $maison,
        ));
    }

    /*********************        Affichage Reservation       *****************************/
    public function afficherReservationAction($num,Request $request)
    {

        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository("HotesBundle:ReservationHotes")->find($num);
        $editForm = $this->createForm('HotesBundle\Form\ReservationHotesType',    $res );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('maisonshotes_index'
                   );
        }

        return $this->render('@Hotes\hotes\showReservation.html.twig', array('reservation' => $res,
            'edit_form' => $editForm->createView(),));

    }

    /****************** Télecharger Reservation sous format PDF *******************/
    public function downloadPdfAction($num)
    {
        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository("HotesBundle:ReservationHotes")->find($num);
        $snappy = $this->get("knp_snappy.pdf");
        $filename = " reservation_num_$num";
        $webSiteUrl = $this->render('@Hotes\hotes\pdf.html.twig', array('reservation' => $res));
        return new  Response(
            $snappy->getOutputFromHtml($webSiteUrl),
            200,
            array(
                'content-Type' => 'application/pdf',
                'content-Disposition' => 'attachment; filename="' . $filename . 'pdf"'
            )
        );
    }

    /******************* 404 Not Found erreur des donnees de reservation *************************/
    public function ErreurAction()
    {
        return $this->render("@Hotes\hotes\ErreurNotFound.html.twig");
    }






}
