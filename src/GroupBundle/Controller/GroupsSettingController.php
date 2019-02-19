<?php
/**
 * Created by PhpStorm.
 * User: Hsine
 * Date: 09/02/2019
 * Time: 17:59
 */

namespace GroupBundle\Controller;


use GroupBundle\Entity\Groups;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GroupsSettingController extends Controller

{


    public function DemandeGroupAction(Request $request, groups $groups)
    {


        $em = $this->getDoctrine()->getManager();
        $membres = $em->getRepository("GroupBundle:Groups")
            ->findDemandeGroup($groups->getId());
        return $this->render('groups/demande.html.twig', array('demandes' => $membres,
            'group' => $groups,
        ));

    }
}