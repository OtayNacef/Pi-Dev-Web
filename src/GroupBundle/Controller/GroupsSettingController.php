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


 public function DemandeGroupAction(Request $request,groups $groups)
    {
        $em = $this->getDoctrine()->getManager();

        $demande = $em->getRepository('GroupBundle:GroupsMembers')->findDemandeGroup($groups);
        $nbrdemande = count($demande);
        $membres = $em->getRepository("GroupBundle:Groups")
            ->findDemandeGroup($groups->getId());
        return $this->render('groups/demande.html.twig', array('demandes' => $membres,
            'group' => $groups, 'nbrdemande' => $nbrdemande
        ));

    }


    public function accepterAction($id)
    {
        {
            $enman = $this->getDoctrine()->getManager();
            $demande = $enman->getRepository('GroupBundle:GroupsMembers')->find($id);
            $group = $enman->getRepository('GroupBundle:Groups')->find($demande->getGroups());
            $n = $group->getNbrMembre();
            $group->setNbrMembre($n + 1);
            $demande->setConfirmation(1);
            $enman->persist($demande);
            $enman->flush();
            return $this->redirectToRoute('groups_index');
        }
    }
}