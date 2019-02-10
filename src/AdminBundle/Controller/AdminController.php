<?php

namespace AdminBundle\Controller;

use BonPlansBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }

    public function afficherCategorieAction(){

        $em= $this ->getDoctrine()->getManager();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->findAll();
        return $this ->render("@Admin\Categorie\affiche.html.twig",array('categories'=>$categorie));

    }
    public function ajouterCategorieAction(Request $request){

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $categorie= new Categorie();

        $form = $this->createFormBuilder($categorie)
            ->add('type', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->getForm();

        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $type = $form['type']->getData();


            $categorie->setName($type);


            $sn = $this->getDoctrine()->getManager();
            $sn->persist($categorie);
            $sn->flush();

        }

        return $this->render('@Admin/Categorie/ajout.html.twig', array(

            'form'=>$form->createView()

        ));
    }
    public function supprimerCategorieAction($id){
        $em =$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->find($id);
        $em->remove($categorie);
        $em->flush();
        return  $this->redirectToRoute("admin_affiche_categorie");
    }
}
