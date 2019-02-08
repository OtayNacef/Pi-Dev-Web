<?php

namespace RelationBundle\Controller;

use Mgilet\NotificationBundle\Entity\Notification;
use RelationBundle\Entity\Demande;
use RelationBundle\Entity\Relation;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('default/home.html.twig');
    }



    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository('UserBundle:User')->findEntitiesByString($requestString);
        if(!$entities) {
            $result['entities']['error'] = "there is no user with this username";
        } else {
//            $nom=$entities->getNom();
//            $prenom=$entities->getPreom();
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities){
        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = [$entity->getUserName(), $entity->getNom(), $entity->getPrenom(), $entity->getImage()];
        }
        return $realEntities;
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

    public function rechercheAction()
    {
        return $this->render('@Relation/Default/recherche.html.twig');
    }

    public function getUserAction()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('interets', 'acceptors', 'requesters', 'sendedDemandes', 'receivedDemandes'));
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($user, null);
        return new JsonResponse($data);
    }

    public function getUserByUsernameAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("UserBundle:User")->findBy(array("username" => $request->get("username")));
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('interets', 'acceptors', 'requesters', 'sendedDemandes', 'receivedDemandes'));
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($user, null);
        return new JsonResponse($data);
    }

    public function resultatAction(Request $request)
    {

        $manager = $this->getDoctrine()->getManager();
        $genre = $request->get("gender");
        $age = $request->get("age");
        if ($age == null) {
            $age[0] = 18;
            $age[1] = 60;
        }
        $occupation = $request->get("occupation");
        $religion = $request->get("religion");
        $pays = $request->get("pays");
        $ville = $request->get("ville");
        $region = $request->get("region");
        $films = $request->get("films");
        $series = $request->get("series");
        $livres = $request->get("livres");
        $musiques = $request->get("musiques");
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $datemin = new \DateTime("now -$age[0] year");
        $datemax = new \DateTime("now -$age[1] year");
        $userList = $manager->getRepository("UserBundle:User")->resultusers($u->getId(), $datemin->format("Y-m-d"), $datemax->format("Y-m-d"), $genre, $occupation, $religion, $pays, $ville, $region, $films, $series, $livres, $musiques);


        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));

        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($userList, null, array('attributes' => array('id', 'nom', 'prenom', 'image', 'pays', 'ville')));
        return new JsonResponse($data);


    }

    public function checkAction(Request $request)
    {
        $user = $request->get("user");
        $touser = $request->get("touser");
        $users = array($user, $touser);
        $manager = $this->getDoctrine()->getManager();
        $x = $manager->getRepository("RelationBundle:Relation")->checkMembers($users);
        $y = $manager->getRepository("RelationBundle:Demande")->checkMembers($users);
        $z = $x + $y;
        return new JsonResponse($z);
    }


}
