<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Message;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

class MessageController extends Controller
{
    public function getMessagesAction(Request $request)
    {
        $touser= $request->get("touser");
        $last = $request->get("last");
        $manager = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $this->updateMessageNotifications($user, $touser);
        $messageList = $manager->getRepository("UserBundle:Message")->fetchMessages($user,$touser,$last);

        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($messageList, null, array('attributes' => array('id','sender'=>['id'], 'receiver' => ['id'],'text','date')));
        return new JsonResponse($data);
    }

    public function getAllMessagesAction(Request $request)
    {
        $mlist = $request->get('mids');
        $manager = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $messageList = $manager->getRepository("UserBundle:Message")->fetchAllMessages($user,$mlist);

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($messageList, null, array('attributes' => array('id', 'sender' => ['id'], 'receiver' => ['id'], 'text', 'date')));
        return new JsonResponse($data);
    }

    public function sendMessageAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $touserid= $request->get("touser");
        $text = $request->get("text");
        $touser = $manager->getRepository("UserBundle:User")->find($touserid);
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $message = new Message();
        $message->setDate(new \DateTime());
        $message->setSender($user);
        $message->setReceiver($touser);
        $message->setText($text);
        $manager->persist($message);
        $manager->flush();
        $this->addNotification($user, $touser, $message);
        return new JsonResponse("DONE !!!");
    }
    public function getChatMemebersAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $users = $manager->getRepository("RelationBundle:Relation")->fetchMembers($user);
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($users, null, array('attributes' => array('id','requester'=> ['id','image','nom','prenom'], 'acceptor' => ['id','image','nom','prenom'])));
        return new JsonResponse($data);
    }

    private function updateMessageNotifications($user, $touser)
    {
        $manager = $this->get('mgilet.notification');
        $notifications = $manager->getUnseenNotifications($user);
        foreach ($notifications as $notif) {
            if ($notif[0]->getSubject() == "Message" && $notif[0]->getLink() == $touser)
                $manager->markAsSeen($user, $notif[0], true);
        }
    }


    private function addNotification($user, $touser, $message)
    {
        $manager = $this->get('mgilet.notification');
        $idm = $message->getId();
        $notif = $manager->createNotification('Message');
        $notif->setMessage($idm);
        $notif->setLink($user->getId());
        $manager->addNotification(array($touser), $notif, true);
    }
}
