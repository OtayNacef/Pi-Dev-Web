<?php

namespace RelationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class NotificationController extends Controller
{
    public function getMessageNotificationAction()
    {
        $manager = $this->get('mgilet.notification');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $notifications = $manager->getUnseenNotifications($user);

        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('notification', 'notifiableEntity', 'notifiableNotifications'));
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($notifications);
        return new JsonResponse($data);
    }

    public function getDemandesAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $list = $manager->getRepository("RelationBundle:Demande")->fetchDemandes($user);

        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('sendedDemandes', 'receivedDemandes'));

        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($list, null, array('attributes' => array('id', 'sender' => ['id', 'image', 'nom', 'prenom'])));
        return new JsonResponse($data);

    }

    public function makeAcceptsAsSeenAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $manager = $this->get('mgilet.notification');
        $notifications = $manager->getUnseenNotifications($user);
        foreach ($notifications as $notif) {
            if ($notif[0]->getSubject() == "Accept") ;
            $manager->markAsSeen($user, $notif[0], true);
        }
        return new JsonResponse("OK");
    }
}
