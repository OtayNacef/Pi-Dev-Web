<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\Form\BonPlanType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DetailBonPlanController extends Controller
{
    public function afficherAction(Request $request,$id)
    {
        $em= $this ->getDoctrine()->getManager();
        $bonplan=$em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $editForm=$this->createFormBuilder($bonplan)
            ->add('name', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('phone', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('adresse', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('note', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('etoile', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('prix', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('image', FileType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label'=>'Valider', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))

            ->getForm();
        //$editForm = $this->createForm('BonPlansBundle\Form\BonPlanType', $bonplan);
        $editForm->handleRequest($request);
        return $this->render('BonPlansBundle:BonPlan:detailbonplan.html.twig',
            array("bonplan"=>$bonplan,"edit_form"=>$editForm->createView()));
    }
}
