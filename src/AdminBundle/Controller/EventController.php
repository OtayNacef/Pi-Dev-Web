<?php


namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller

{
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }

    public function eventIndexAction()
    {

        $events = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findAll();
        return $this->render('@Admin/Default/events.html.twig', array('events' => $events));

    }

    public function deleteEventAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventBundle:Evenement')->find($id);
        $sn->remove($event);
        $sn->flush();

        return $this->redirectToRoute('event_homepage');

    }

}