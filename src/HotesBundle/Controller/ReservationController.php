<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\MaisonsHotes;
use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Skies\SkiesQRcodeBundle\Generator\Generator;


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
            if ($maison->getCapacites() > $nb_per) {
                $prix = $maison->getPrix();
                $diff = $d_fin->diff($d_deb)->format("%a");

                $reservation->setPrix($prix * $diff * $nb_per);
                $reservation->setMaisonsHotes($maison);
                $reservation->setUser($u);
                $am->persist($reservation);
                $am->flush();


                return $this->redirectToRoute('afficherReservation', array('num' => $reservation->getNumeroReservation()));
            } else {
                return $this->redirectToRoute('erreur');
            }
        }
        return $this->render('@Hotes\hotes\Reservation.html.twig', array(
            'reservation' => $reservation,
            'form' => $form->createView(),
            'maison' => $maison,
        ));
    }

    /*********************        Affichage Reservation       *****************************/
    public function afficherReservationAction($num, Request $request)
    {

        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository("HotesBundle:ReservationHotes")->find($num);
        $maison = $am->getRepository("HotesBundle:MaisonsHotes")->find($res->getMaisonsHotes()->getId());

        $user = $this->getUser();
        $options = array(
            'code' => $user->getUsername() . $res->getMaisonsHotes()->getNom(),
            'type' => 'qrcode',
            'color' => 'black',
            'format' => 'html',
        );

        $barcode = $this->get('skies_barcode.generator')->generate($options);

        $editForm = $this->createForm('HotesBundle\Form\ReservationUpdateType', $res);
        $editForm->handleRequest($request);
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //$this->ReserverAction($num,$request);
            $d_deb = $editForm->get('date_debut')->getData();
            $d_fin = $editForm->get('date_fin')->getData();
            $nb_per = $editForm->get('nb_personne')->getData();
            if ($d_deb > $d_fin) {
                return $this->redirectToRoute('erreur');

            }

            $prix = $maison->getPrix();
            $diff = $d_fin->diff($d_deb)->format("%a");

            $res->setPrix($prix * $diff * $nb_per);
            $res->setMaisonsHotes($maison);
            $res->setUser($u);
            $am->persist($res);
            $am->flush();
            //  $this->getDoctrine()->getManager()->flush();
            // return $this->redirectToRoute('hotes_show_reservation',array('maison'=>$maison)

        }

        return $this->render('@Hotes\hotes\showReservation.html.twig', array('reservation' => $res,
            'maison' => $maison, 'barcode' => $barcode,
            'edit_form' => $editForm->createView()));

    }

    /****************** Télecharger Reservation sous format PDF *******************/
    public function downloadPdfAction($num)
    {
        $user = $this->getUser();
        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository("HotesBundle:ReservationHotes")->find($num);
//BarCode
        $options = array(
            'code' => $user->getUsername() . $res->getMaisonsHotes()->getNom(),
            'type' => 'qrcode',
            'color' => 'black',
            'format' => 'html',
        );

        $barcode = $this->get('skies_barcode.generator')->generate($options);


        $snappy = $this->get("knp_snappy.pdf");
        $filename = " reservation_num_$num";
        $webSiteUrl = $this->render('@Hotes\hotes\pdf.html.twig', array('reservation' => $res, 'barcode' => $barcode));
        return new  Response(
            $snappy->getOutputFromHtml($webSiteUrl),
            200,
            array(
                'content-Type' => 'application/pdf',
                'content-Disposition' => 'attachment; filename="' . $filename . '.pdf"'
            )
        );
    }

    /******************* 404 Not Found erreur des donnees de reservation *************************/
    public function ErreurAction()
    {
        return $this->render("@Hotes\hotes\ErreurNotFound.html.twig");
    }


}
