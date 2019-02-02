<?php
/**
 * Created by PhpStorm.
 * User: Magiko
 * Date: 01/02/2019
 * Time: 14:44
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\Message;

class MessageController extends Controller
{

    public function getMessagesAction(Request $request)
    {
        $touser= $request->get("touser");
        $last = $request->get("last");
        $manager = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
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

        return $this->render('@User/x.html.twig', array(
            'message' => $messageList,
        ));
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
        return new JsonResponse("DONE !!!");
    }
    public function getChatMemebersAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        $users = $manager->getRepository("UserBundle:Relation")->fetchMembers($user);
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($users, null, array('attributes' => array('id','requester'=> ['id','image','nom','prenom'], 'acceptor' => ['id','image','nom','prenom'])));
        return new JsonResponse($data);
    }

}