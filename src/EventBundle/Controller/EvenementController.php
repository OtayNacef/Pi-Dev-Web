<?php

namespace EventBundle\Controller;


use EventBundle\Entity\Evenement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use UserBundle\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


use EventBundle\Form\EvenementType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

/**
 * Evenement controller.
 *
 * @Route("evenement")
 */
class EvenementController extends Controller
{


    public function __construct()
    {}
    public function indexAction(Request $request)
    {

        $user=$this->container->get('security.token_storage')->getToken()->getUser();


        $em= $this->getDoctrine()->getManager();

        $evenement = new Evenement();
        $now=new\DateTime('now');

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $evenement->setResponsable($user);
            $em->persist($evenement);
            $em->flush();
            //   $this->envoyerEmail($evenement);
            //    return array('id' => $evenement->getId());
//            return $this->redirectToRoute('evenement_index');

        }

        $evenements = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findAll();
        $myevents = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findByResponsable($user);

        return $this->render('@Event/evenement/index.html.twig',array('myevents'=>$myevents,'evenements' => $evenements, 'form' => $form->createView()));
    }



    public function detailsAction($id){

        $event = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);
        return $this->render('todos/details.html.twig', array(

            'todo'=>$event

        ));

    }

    public function editAction($id, Request $request){

        $now = new\DateTime('now');
        $event = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        $event->setNomEvenement($event->getNomEvenement());
        $event->setType($event->getType());
        $event->setAdr($event->getAdr());
        $event->setDescription($event->getDescription());
        $event->setImage($event->getImage());
        $event->setPrix($event->getPrix());
        $event->setNbreplace($event->getNbreplace());
        $event->setDateDebut($event->getDateDebut());
        $event->setDateFin($event->getDateFin());



        $form = $this->createFormBuilder($event)
            ->add('nomEvenement')
            ->add('type')
            ->add('adr')
            ->add('description',TextareaType::class)
            ->add('image')
            ->add('nbreplace')
            ->add('prix')
            ->add('dateDebut')
            ->add('dateFin')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $nom = $form['nomEvenement']->getData();
            $type = $form['type']->getData();
            $adr = $form['adr']->getData();
            $desc = $form['description']->getData();
            $image = $form['image']->getData();
            $prix = $form['prix']->getData();
            $nbr = $form['nbreplace']->getData();
            $dd = $form['dateDebut']->getData();
            $df = $form['dateFin']->getData();



            $sn = $this->getDoctrine()->getManager();
            $event = $sn->getRepository('EventBundle:Evenement')->find($id);

            $event->setNomEvenement($nom);
            $event->setType($type);
            $event->setAdr($adr);
            $event->setDescription($desc);
            $event->setImage($image);
            $event->setPrix($prix);
            $event->setNbreplace($nbr);
            $event->setDateDebut($dd);
            $event->setDateFin($df);
            $sn->flush();
            $this->addFlash(
                'notice',
                'todo updated'
            );

//            return $this->redirectToRoute('todo_list');
        }

        return $this->render('@Event/evenement/edit.html.twig', array(

            'event'=>$event,
            'form'=>$form->createView()
        ));

    }


    public function eventAction()
    {
        return $this->render("@Event/evenement/editevent.html.twig");
    }


    public function modifyAction(Request $request )
    {

        $em= $this->getDoctrine()->getManager();
        $titre = $request->get('event') ;
        $start = $request->get('datedebut') ;
        $end = $request->get('datefin') ;
        $evenement = $em->getRepository(Evenement::class)->findOneBy(array("nomEvenement"=>$titre));
        $evenement->setDateDebut(new \DateTime($start));
        $evenement->setDateFin(new \DateTime($end));
        $em->merge($evenement);
        $em->flush();
        return new Response("yes");
    }

    public function signalerAction($id){

        $manager = $this->getDoctrine()->getManager();
        $eventS = $this->getDoctrine()->getRepository('EventBundle:Evenement')->findBy($id);
        $eventS->setNbrSignal($eventS->getNbrSignal()+1);
        $manager->persist($eventS);
        $manager->flush();

    }

//debut fonction recherche
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository('EventBundle:Evenement')->findEntitiesByString($requestString);
        if(!$entities) {
            $result['entities']['error'] = "there is no event";
        } else {
//            $nom=$entities->getNom();
//            $prenom=$entities->getPreom();
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities){
        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = [$entity->getnomEvenement(), $entity->getImage(), $entity->getPrix()];
        }
        return $realEntities;
    }
//fin fonction recherche

    public function editEventAction($id, Request $request){

        $now = new\DateTime('now');
        $event = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        $event->setNomEvenement($event->getNomEvenement());
        $event->setType($event->getType());
        $event->setAdr($event->getAdr());
        $event->setDescription($event->getDescription());
        $event->setImage($event->getImage());
        $event->setPrix($event->getPrix());
        $event->setNbreplace($event->getNbreplace());
        $event->setDateDebut($event->getDateDebut());
        $event->setDateFin($event->getDateFin());



        $form = $this->createFormBuilder($event)
            ->add('nomEvenement')
            ->add('type')
            ->add('adr')
            ->add('description',TextareaType::class)
            ->add('image')
            ->add('nbreplace')
            ->add('prix')
            ->add('dateDebut')
            ->add('dateFin')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $nom = $form['nomEvenement']->getData();
            $type = $form['type']->getData();
            $adr = $form['adr']->getData();
            $desc = $form['description']->getData();
            $image = $form['image']->getData();
            $prix = $form['prix']->getData();
            $nbr = $form['nbreplace']->getData();
            $dd = $form['dateDebut']->getData();
            $df = $form['dateFin']->getData();



            $sn = $this->getDoctrine()->getManager();
            $event = $sn->getRepository('EventBundle:Evenement')->find($id);

            $event->setNomEvenement($nom);
            $event->setType($type);
            $event->setAdr($adr);
            $event->setDescription($desc);
            $event->setImage($image);
            $event->setPrix($prix);
            $event->setNbreplace($nbr);
            $event->setDateDebut($dd);
            $event->setDateFin($df);
            $sn->flush();
            $this->addFlash(
                'notice',
                'todo updated'
            );

//            return $this->redirectToRoute('todo_list');
        }

        return $this->render('@Event/evenement/editevent.html.twig', array(

            'event'=>$event,
            'form'=>$form->createView()
        ));

    }

    








}







