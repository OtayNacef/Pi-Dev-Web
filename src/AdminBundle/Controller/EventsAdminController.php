<?php
namespace AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventsAdminController extends Controller
{
    /*** PARTIE ADMIN POUR GESTION D'EVENEMENT********/
    public function showEventsAction(Request $request)
    {
        // ***************************   Liste des événements **************************//
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("EventBundle:Evenement")->findAll();
             return $this->render('@Admin/events/events.html.twig', array('events' => $events));


    }
    /*****************     Supprimer les evenements par l'admin         *******************/
    public function deleteEventsAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $events = $am->getRepository("EventBundle:Evenement")->find($id);
        $am->remove($events);
        $am->flush();
        return $this->redirectToRoute("admin_homepage_events");
    }
    public function rechercheEventAdminAction(Request $request){
        $nom=$request->get("nom");
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("EventBundle:Evenement")->ajaxRecherche($nom);
        return new Response($this->renderView('@Admin/events/rechercheAdmin.html.twig', array('events' => $events)));
    }

}


