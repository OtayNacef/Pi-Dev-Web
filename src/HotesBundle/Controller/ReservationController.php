<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\MaisonsHotes;
use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{
    function  AfficherAction(){
        $am=$this->getDoctrine()->getManager();
        $hote=$am->getRepository("HotesBundle:MaisonsHotes")->findAll();
        return $this->render("@Hotes\hotes\afficheHote.html.twig", array('hote'=>$hote));
    }

    function ReserverAction(Request $request){
        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        $am=$this->getDoctrine()->getManager();
        $reservation= new ReservationHotes();
        $form=$request->isMethod("POST");
        if ($form -> isSubmitted() && $form->isValid())
        {
            $reservation->setDateDebut(new \DateTime($request->get('date_debut')));
            $reservation->setDateFin(new \DateTime($request->get('date_fin')));
            $reservation->setNbPersonne($request->get('nb_place'));
            $reservation->setUser($u);
            //$reservation->setMaisonsHotes($h);
            $am->persist($reservation);
            $am->flush();
            return $this->redirectToRoute("maisonshotes_reservation");
        }
        return $this->render("@Hotes\hotes\show.html.twig");
    }



}
