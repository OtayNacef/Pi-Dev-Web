<?php

namespace AdminBundle\Controller;

use BonPlansBundle\Entity\Categorie;
use BonPlansBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }

    public function afficherCategorieAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->findAll();

//      $em = $this->getDoctrine()->getManager();
//        $categorie = $em->getRepository("BonPlansBundle:Categorie")->find($id);
//
//        $edit_form = $this->createForm(CategorieType::class, $categorie);
//        $edit_form -> handleRequest($request);
//
//        if($edit_form->isSubmitted() && $edit_form->isValid()) {
//
//            $type = $edit_form['type']->getData();
//
//            $categorie->setType($request->get('type'));
//
//
//            $em->flush();
//            return $this->redirectToRoute('admin_affiche_categorie');
//        }
        // return $this->render('@Admin/Categorie/ajout.html.twig',array(,"categorie"=>$categorie));
//        return $this ->render("@Admin\Categorie\affiche.html.twig",array(
////            'categories'=>$categorie,
//            'edit_form'=>$edit_form->createView()));
        return $this->render('@Admin/Categorie/affiche.html.twig', array("categorie" => $categorie));

    }

    public function ajouterCategorieAction(Request $request)
    {


        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->findAll();

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = $form['type']->getData();

            $categorie->setType($type);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($categorie);
            $sn->flush();

            return $this->redirectToRoute("admin_affiche_categorie");
        }

        return $this->render('@Admin/Categorie/ajout.html.twig', array(

            'form' => $form->createView()

        ));
        // return $this->render('@Admin/Categorie/Ajout.html.twig',array("categorie"=>$categorie));
    }

    public function supprimerCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_categorie");
    }

    public function modifierCategorieAction(Request $request, $id)
    {

    }
}
