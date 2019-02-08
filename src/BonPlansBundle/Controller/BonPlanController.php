<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\Entity\BonPlan;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
//use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use UserBundle\Entity\User;
use BonPlansBundle\Form\RestaurantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BonPlanController extends Controller
{
    public function indexAction()
    {
        return $this->render("@BonPlans\BonPlan\listbonplan.html.twig");
    }

    public function CreateAction(Request $request){

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $bonplan = new BonPlan();

        $form = $this->createFormBuilder($bonplan)
            ->add('name', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('etoile', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('note', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('type', ChoiceType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
//            ->add('image', FileType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('phone', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextType::class, array('attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('adresse', TextType::class, array('attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label'=>'create todo', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
            ->getForm();

        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $name = $form['name']->getData();
            $phone = $form['phone']->getData();
            $description = $form['description']->getData();
            $note = $form['note']->getData();
            $etoile = $form['etoile']->getData();
            $image = $form['image']->getData();
            $adresse = $form['adresse']->getData();
            $type = $form['type']->getData();

            $bonplan->setName($name);
            $bonplan->setType($type);
            $bonplan->setImage($image);
            $bonplan->setNote($note);
            $bonplan->setEtoile($etoile);
            $bonplan->setAdresse($adresse);
            $bonplan->setDescription($description);
            $bonplan->setPhone($phone);
            $bonplan->setUser($user);


            $sn = $this->getDoctrine()->getManager();
            $sn->persist($bonplan);
            $sn->flush();

//            $this->addFlash(
//                'notice',
//                'todo added'
//            );

//            return $this->redirectToRoute('todo_list');
        }

        return $this->render('@BonPlans/BonPlan/ajout.html.twig', array(

            'form'=>$form->createView()

        ));
    }

   public function AfficherAction()
    {
        $em= $this ->getDoctrine()->getManager();
        $bonplan =$em->getRepository("BonPlansBundle:BonPlan")->findAll();
        return $this ->render("@BonPlans\BonPlan\listrestaurant.html.twig",array('bonsplans'=>$bonplan));

    }


    public function AjouterAction(Request $request){
        $bonplan = new BonPlan();
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($request ->isMethod("POST")  ){


                $bonplan->setName($request->get('name'));
                $bonplan->setAdresse($request->get('adresse'));
                $bonplan->setDescription($request->get('description'));
                $bonplan->setPhone($request->get('phone'));
                $bonplan->setUser($user);
                $bonplan->setPrix($request->get('prix'));
                $bonplan->setEtoile($request->get('etoile'));
                $bonplan->setNote($request->get('note'));
                $bonplan->setType($request->get('choix'));
                $bonplan->setNote($request->get('note'));

                $em->persist($bonplan);
                $em->flush();
            return $this->redirectToRoute('bon_plans_homepage');

        }
            return $this->render("@BonPlans\BonPlan\ajout.html.twig",array("bonplan"=>$bonplan));

    }

   public function ModifierAction(Request $request,$name)
    {
        $em=$this->getDoctrine()->getManager();
        $restaurant=$em->getRepository("BonPlansBundle:BonPlan")->find($name);
        $form= $this->createForm(RestaurantType::class,$restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('bon_plans_ajouter_restaurant');
        }

        return $this->render('@BonPlans\BonPlan\ajout.html.twig',['f' => $form->createView()]);
    }

    public function SupprimerAction($name){
        $em =$this->getDoctrine()->getManager();
        $restaurant=$em->getRepository("BonPlansBundle:BonPlan")->find($name);
        $em->remove($restaurant);
        $em->flush();
        return  $this->redirectToRoute("bon_plans_afficher_restaurant");
    }

}
