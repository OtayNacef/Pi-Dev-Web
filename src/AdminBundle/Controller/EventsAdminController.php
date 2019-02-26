<?php

namespace AdminBundle\Controller;

use EventBundle\Entity\Evenement;
use EventBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class EventsAdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/events/events.html.twig');
    }

    public function afficherEvenementAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EventBundle:Evenement")->findAll();
        return $this->render('@Admin/events/events.html.twig', array("event" => $event));

    }


    public function supprimerEvenementAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EventBundle:Evenement")->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_event");
    }


}
