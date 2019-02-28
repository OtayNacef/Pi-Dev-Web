<?php

namespace AdminBundle\Controller;

use BonPlansBundle\Entity\Categorie;
use BonPlansBundle\Form\CategorieType;
use BonPlansBundle\Form\CategorieUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CategorieAdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }



    public function supprimerCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_categorie");
    }


    public function afficherCategorieAction(Request $request){

        $em= $this ->getDoctrine()->getManager();



//        if ($request->isMethod('POST')) {
//            if ($request->request->has('idpubmodal')) {
//                $p = $em->getRepository(Categorie::class)->find($request->get("idpubmodal"));
//                $p->setType($request->get('contenup'));
//                $em->persist($p);
//                $em->flush();
//                return $this->redirectToRoute("admin_affiche_categorie");
//            }
//        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em= $this ->getDoctrine()->getManager();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->findAll();


        $newcategorie= new Categorie();
        $form= $this->createForm(CategorieType::class,$newcategorie);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $type = $form['type']->getData();

            $exist=$em->getRepository('BonPlansBundle:Categorie')->findByType($type);

            if( empty($exist) )
            {
                $newcategorie->setType($type);


            $sn = $this->getDoctrine()->getManager();
            $sn->persist($newcategorie);
            $sn->flush();
                return  $this->redirectToRoute("admin_affiche_categorie");
            }

        }
        return $this->render('@Admin/Categorie/affiche.html.twig',array("categorie"=>$categorie,'form'=>$form->createView(),
            ));

    }

    public function ModifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->find($id);
        $form= $this->createForm(CategorieUpdateType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute('admin_affiche_categorie');
        }

        return $this->render('@Admin/Categorie/affiche.html.twig',array("f" => $form->createView()));
    }
}
