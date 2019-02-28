<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\BonPlansBundle;
use BonPlansBundle\Entity\BonPlan;
use BonPlansBundle\Entity\RatingBonPlan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class LikeDislikeController extends Controller
{
    function LikedAction($idBonPlan)
    {
        $like = new RatingBonPlan();

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $bonplan=$em->getRepository("BonPlansBundle:BonPlan")->find($idBonPlan);
        $rating=$em->getRepository("BonPlansBundle:RatingBonPlan")->findBy(array('bonplan'=>$bonplan,'user'=>$user));
            if(!$rating){
        $like->setUser($user);
        $like->setBonplan($bonplan);
        $like->setVote(true);
        $em->persist($like);
        $em->flush();
            }
            else if($rating && $rating[0]->getVote() == false){
                $like=$em->getRepository("BonPlansBundle:RatingBonPlan")->find($rating[0]);
                $em->remove($like);
                $em->flush();
                $like2 = new RatingBonPlan();
                $like2->setUser($user);
                $like2->setBonplan($bonplan);
                $like2->setVote(true);
                $em->persist($like2);
                $em->flush();
            }
            else{
                $like=$em->getRepository("BonPlansBundle:RatingBonPlan")->find($rating[0]);
                $em->remove($like);
                $em->flush();
            }

        return $this->redirectToRoute('bon_plans_afficher_bon_plan');

    }
    function DislikedAction($idBonPlan)
    {
        $like = new RatingBonPlan();

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $bonplan=$em->getRepository("BonPlansBundle:BonPlan")->find($idBonPlan);
        $rating=$em->getRepository("BonPlansBundle:RatingBonPlan")->findBy(array('bonplan'=>$bonplan,'user'=>$user));
        if(!$rating){
            $like->setUser($user);
            $like->setBonplan($bonplan);
            $like->setVote(false);
            $em->persist($like);
            $em->flush();
        }
        else if($rating && $rating[0]->getVote() == true){
            $like=$em->getRepository("BonPlansBundle:RatingBonPlan")->find($rating[0]);
            $em->remove($like);
            $em->flush();
            $like2 = new RatingBonPlan();
            $like2->setUser($user);
            $like2->setBonplan($bonplan);
            $like2->setVote(false);
            $em->persist($like2);
            $em->flush();
        }
        else{
            $like=$em->getRepository("BonPlansBundle:RatingBonPlan")->find($rating[0]);
            $em->remove($like);
            $em->flush();
        }

        return $this->redirectToRoute('bon_plans_afficher_bon_plan');

    }

}
