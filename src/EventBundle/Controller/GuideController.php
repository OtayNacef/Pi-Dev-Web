<?php
/**
 * Created by PhpStorm.
 * User: soumaya
 * Date: 18/02/2019
 * Time: 20:30
 */

namespace EventBundle\Controller;


use EventBundle\Entity\Guide;
use EventBundle\Form\GuideType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class GuideController extends Controller
{


    function deleteGuideAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $guide = $em->getRepository("EventBundle:Guide")->find($id);
        $em->remove($guide);
        $em->flush();
        return $this->redirectToRoute("list_guide");


    }


    public function createGuideAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository('EventBundle:Evenement')->find($id);
        if ($request->isMethod('post')) {
            $guide = new Guide();
            $guide->setNom($request->get('nom'));
            $guide->setPrenom($request->get('prenom'));
            $guide->setEvenement($evenement);
            $guide->setMail($request->get('mail'));
            $guide->setTel((int)$request->get('tel'));
            $guide->setEventType($evenement->getType());
            $em->persist($guide);
            $evenement->setPrix($evenement->getPrix() + 5);
            $em->merge($evenement);
            $em->flush();
            return $this->redirectToRoute("list_guide");
        }
        return $this->render("@Event/guide/createGuide.html.twig", array("event" => $evenement));

    }


    public function editGuideAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $guide = $em->getRepository('EventBundle:Guide')->find($id);
        $events = $em->getRepository('EventBundle:Evenement')->findBy(array('type' => $guide->getEventType()));
//        $now = new\DateTime('now');
//
        if ($request->isMethod('post')) {
            $event = $em->getRepository('EventBundle:Evenement')->find($request->get('evenement'));
            if ($event->getDateDebut() > $guide->getEvenement()->getDateFin()) {
                $guide->setNom($request->get('nom'));
                $guide->setPrenom($request->get('prenom'));
                $guide->setMail($request->get('mail'));
                $guide->setTel($request->get('tel'));
                $guide->setEventType($event->getType());
                $guide->setEvenement($event);
                return $this->redirectToRoute("list_guide");
            } else {
               // $this->addFlash("error", "Veuillez verifier que la date de l'evenemennt est superieur a la date de l'ancien evenement");
                $this->get('session')->getFlashBag()->set('error', 'Veuillez verifier que la date de l\'evenemennt est superieur a la date de l\'ancien evenement');
                return $this->render('@Event/guide/editGuide.html.twig', array(
                    'guide' => $guide,
                    'events' => $events));
            }
        }
        return $this->render('@Event/guide/editGuide.html.twig', array(
            'guide' => $guide,
            'events' => $events));

    }


    public function listGuideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository("EventBundle:Guide")->findAll();
        return $this->render('@Event\guide\listGuide.html.twig', array("guides" => $guides));
    }
}