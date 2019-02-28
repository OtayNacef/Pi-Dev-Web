<?php

namespace EventBundle\Controller;


use DateTime;
use EventBundle\Entity\Evenement;
use EventBundle\Entity\Participants;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Form\EvenementType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Swift_Message;


class EvenementController extends Controller
{


    public function __construct()
    {

    }

//----------------------------AJOUT ET AFFICHAGE D'UN EVENEMENT----------------------------------------------------------

    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // $file stores the uploaded PDF file
            /** @var UploadedFile $file */
            $file = $evenement->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
            $evenement->setImage($fileName);

            $evenement->setResponsable($user);
            if ($evenement->getDateFin() > $evenement->getDateDebut()) {
                $diff = date_diff($evenement->getDateFin(), $evenement->getDateDebut());
                if ($diff->m < 6) {
                    $dateCreation = new\DateTime('now');
                    $evenement->setDateCreation($dateCreation);
                    $em->persist($evenement);
                    $em->flush();
                }
            }

        }

        $evenements = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findAll();
        $myevents = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findByResponsable($user);

        return $this->render('@Event/evenement/index.html.twig', array('myevents' => $myevents, 'evenements' => $evenements, 'form' => $form->createView()));
    }


    //----------------------------------SUPPRESION D'UN EVENEMENT-----------------------------------------------------------
    public function deleteAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventBundle:Evenement')->find($id);
        $sn->remove($event);
        $sn->flush();
        return $this->redirectToRoute('evenement_index');

    }

//----------------------------------PAGE DE DETAILLE D4AUN EVENEMENT (PAERTICIPER)---------------------------------------------------
    public function detailAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventBundle:Evenement')->find($id);


        return $this->render('@Event/evenement/detail.html.twig', array('event' => $event));

    }

//----------------------------------------MODIFICATION D'UN EVENEMENT-----------------------------------------------------------------

    public function editAction($id, Request $request)
    {


//        $now = new\DateTime('now');
        $event = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        if ($request->isMethod("post")) {
            $event->setNomEvenement($request->get('nomEvenement'));
            $event->setType($request->get('type'));
            $event->setAdr($request->get('adr'));
            $event->setDescription($request->get('description'));
            if ($request->files->get('image')) {
                /** @var UploadedFile $file */
                $file = $request->files->get('image');
                $fileName = md5(uniqid()). '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $event->setImage($fileName);

            }

            $event->setPrix($request->get('prix'));
            $event->setNbreplace($request->get('nbreplace'));
            $event->setNbreplace($request->get('telresponsable'));
            $event->setDateDebut(new\DateTime($request->get('dateDebut')));
            $event->setDateFin(new\DateTime($request->get('dateFin')));
            $sn = $this->getDoctrine()->getManager();
            $sn->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('@Event/evenement/editEvent.html.twig', array(

            'event' => $event,
        ));

    }



//------------------------------------------DEBUT FONCTION RECHERCHE---------------------------------------------------------
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('EventBundle:Evenement')->findEntitiesByString($requestString);
        if (!$entities) {
            $result['entities']['error'] = "PAS D'EVENEMENT ";
        } else {
//            $nom=$entities->getNom();
//            $prenom=$entities->getPreom();
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = [
                $entity->getnomEvenement(),
                $entity->getImage(),
                $entity->getPrix(),
                $entity->getDescription(),
                $entity->getType(),
                $entity->getDateDebut()->format("Y-m-d"),
                $entity->getDateFin()->format("Y-m-d"),
                $entity->getResponsable()->getNom()

            ];
        }
        return $realEntities;
    }

//-----------------------------------------FIN FONCTION RECHERCHE--------------------------------------------------------------------

//-------------------------------------FONCTION PARTICIPER ----------------------------------------------------------------------
    public function participerAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $m = $this->getDoctrine()->getManager();
        $event = $m->getRepository('EventBundle:Evenement')->find($id);
        $existe = $m->getRepository('EventBundle:Participants')->findBy(array('userid' => $user, 'id' => $id));
        if ($existe == null) {
            $m->persist($event);
            $participe = new Participants();
            $participe->setEvenement($event);
            $participe->setUserid($user);
            $participe->setConfirmation(0);
            $participe->setDateInscrit(new \DateTime('now'));
            $m->persist($participe);
            $m->flush();

            return $this->redirectToRoute('list_participate');
        }
    }
//-------------------------------------------LISTE DES EVENEMENTS PARTCICIPEE-----------------------------------------------
    public function listParticipateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("EventBundle:Participants")->findAll();
        return $this->render('@Event\evenement\participate.html.twig', array("events" => $events[0]));
    }
//------------------------------------------TELECHARGER TICKET-------------------------------------------------------------
    public function pdfEventAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $participation = $em->getRepository('EventBundle:Participants')->find($id);
        $snappy=$this->get("knp_snappy.pdf");
        $html=$this->renderView("@Event/evenement/pdfEvent.html.twig",array(
            "Title"=> "Reservation",'participation'=>$participation
        ));


        $filename="participation";

        return new Response(
            $snappy->getOutputFromHtml($html),

            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"'
            )
        );
    }
//---------------------------------------------ANNULER LA PARTICIPATION-----------------------------------------------------------
    public function annulerISCRIAction($id)
    {

        $user = $this->getUser();
        $idu = $user->getId();
        $event = $this->getDoctrine()->getManager();
        $invitation = $event->getRepository('EventBundle:Participants')->findOneBy(array('userid' => $idu, 'id' => $id, 'confirmation' => false));
        $event->remove($invitation);
        $event->flush();

        return $this->redirectToRoute('evenement_index', array('id' => $id));

    }

//--------------------------------------BUNDLE EXTERNE : SWIFTMAILER DEVELOPEE(AVEC CODE DE CONFIRMATION°---------------------------------------------------------
    public function envoyerMailAction($id){
        $evenement = $this->getDoctrine()->getRepository('EventBundle:Participants')->findOneBy(['id'=>$id]);
        $random =''.$this->generateRandomString();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587,'tls'))
            ->setUsername('esprit.worldfriendship@gmail.com')
            ->setPassword('sassouki');
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Confirmation du participation'))
            ->setFrom('esprit.worldfriendship@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    '@Event/Evenement/Confirmation.html.twig',
                    array('name' => $user->getNom())
                ).'=> '.$random,
                'text/html'
            )

        ;
        /* @var $mailer \Swift_Mailer */
        $evenement->setChampsConfirmation($random);
        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();
        $mailer->send($message);


        return $this->listParticipateAction();

    }
//----------------------------------FONCTION RANDOM DE CODE ENVOYER PAR MAIL-----------------------------------------------------
    public function generateRandomString($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
//-----------------------------------------FONCTION CONFIRMATION-------------------------------------------------------------
    public function envoyerConfirmationAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $code = $request->request->get('code');
            $exists = $this->getDoctrine()->getRepository('EventBundle:Participants')->findOneBy([

                'champsConfirmation' => $code,

            ]);


            if (!empty($exists)){
                $event = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findOneBy(
                    ['id'=>$exists->getEvenement()->getId()]);

                $event->setNbreplace($event->getNbreplace() - 1);

                $exists->setConfirmation(True);

                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->persist($exists);
                $em->flush();
                $serializer = new Serializer([new ObjectNormalizer()]);

                $data = $serializer->normalize([
                    'id' => 'id',
                ]);
                return new JsonResponse($data);
            } else
                return new JsonResponse(null);
        }
        return new JsonResponse();
    }

//----------------------------------------FONCTION DE MODIFICATION DANS LA CALENDRIER-----------------------------------------------------
    public function modifyAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $titre = $request->get('event');
        $start = $request->get('datedebut');
        $end = $request->get('datefin');
        $user = $request->get('user');
        $evenement = $em->getRepository(Evenement::class)->findOneBy(array("nomEvenement" => $titre));
        if ($user != $evenement->getResponsable()->getId()) {
            return new Response("no");
        }
        $evenement->setDateDebut(new \DateTime($start));
        $evenement->setDateFin(new \DateTime($end));
        $em->merge($evenement);
        $em->flush();
        return new Response("yes");
    }

}







