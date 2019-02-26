<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\Form\BonPlanType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class DetailBonPlanController extends Controller
{
    public function afficherAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $editForm = $this->createForm(BonPlanType::class, $bonplan);
        $bonplan->setImage(new File($this->getParameter('BonPlan') . '/' . $bonplan->getImage()));
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            /** @var UploadedFile $file
             */
            $file = $bonplan->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                , $fileName);
            $bonplan->setImage($fileName);
            $em->flush();

            return $this->redirectToRoute('bon_plans_afficher_bon_plan');
        }


        return $this->render('BonPlansBundle:BonPlan:detailbonplan.html.twig',
            array("bonplan" => $bonplan, "edit_form" => $editForm->createView()));
    }

    public function deleteBonPlanAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $em->remove($bonplan);
        $em->flush();

        return $this->redirectToRoute('bon_plans_afficher_bon_plan');

    }
}
