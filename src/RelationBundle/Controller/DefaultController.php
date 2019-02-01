<?php

namespace RelationBundle\Controller;

use Mgilet\NotificationBundle\Entity\Notification;
use RelationBundle\Entity\Demande;
use RelationBundle\Entity\Relation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RelationBundle:Default:index.html.twig');
    }

    public function demandeAction(Request $request)
    {
        $cuser = $this->container->get('security.token_storage')->getToken()->getUser();
        $notificationManager = $this->get('mgilet.notification');
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("UserBundle:User")->find($request->get("user"));
        $demande = new Demande();
        $demande->setSender($cuser);
        $demande->setReceiver($user);
        $demande->setDateDemande(new \DateTime("now"));
        $manager->persist($demande);
        $manager->flush();
        $notification = new Notification();
        $notification->setSubject("Demande");
        $notification->setLink($cuser->getId());
        $notification->setDate(new \DateTime("now"));
        $notification->setMessage($demande->getId());
        $notificationManager->addNotification(array($user),$notification,true);
        return new JsonResponse("OK");
    }

    public function acceptDemandeAction(Request $request)
    {
        $cuser = $this->container->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("UserBundle:User")->find($request->get("user"));
        $demande = $manager->getRepository("RelationBundle:Demande")->find($request->get("demande"));
        $relation = new Relation();
        $relation->setDateRelation(new \DateTime("now"));
        $relation->setAcceptor($cuser);
        $relation->setRequester($user);
        $manager->persist($relation);
        $manager->remove($demande);
        $manager->flush();
        $this->updateDemandeNotifications($cuser,$user);
        $this->addNotification($cuser,$user);
        return new JsonResponse("OK");
    }

    public function rejectDemandeAction(Request $request)
    {
        $cuser = $this->container->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("UserBundle:User")->find($request->get("user"));
        $demande = $manager->getRepository("RelationBundle:Demande")->find($request->get("demande"));
        $manager->remove($demande);
        $manager->flush();
        $this->updateDemandeNotifications($cuser,$user);
        return new JsonResponse("OK");
    }

    private function addNotification($cuser,$user)
    {
        $manager = $this->get('mgilet.notification');
        $notification = new Notification();
        $notification->setDate(new \DateTime("now"));
        $notification->setLink($cuser->getImage());
        $notification->setSubject("Accept");
        $notification->setMessage($cuser->getNom()." ".$cuser->getPrenom());
        $manager->addNotification(array($user),$notification,true);
    }

    private function updateDemandeNotifications($cuser,$user)
    {
        $manager = $this->get('mgilet.notification');
        $notifications = $manager->getUnseenNotifications($cuser);
        foreach ($notifications as $notif)
        {
            if($notif[0]->getSubject() == "Demande" && $notif[0]->getLink() == $user->getId());
            $manager->markAsSeen($cuser,$notif[0],true);
        }
    }
}
