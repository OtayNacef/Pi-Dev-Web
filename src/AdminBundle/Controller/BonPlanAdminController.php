<?php

namespace AdminBundle\Controller;

use BonPlansBundle\Entity\Categorie;
use BonPlansBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BonPlanAdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/Default/index.html.twig');
    }



    public function supprimerBonPlanAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $em->remove($bonplan);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_bonsplans");
    }


    public function affichebonsplansAction(Request $request){

        $em= $this ->getDoctrine()->getManager();
        $bonsplans=$em->getRepository("BonPlansBundle:BonPlan")->findAll();


        return $this->render('@Admin/BonsPlans/afficheBonsPlans.html.twig',array("bonsplans"=>$bonsplans));

    }



}
