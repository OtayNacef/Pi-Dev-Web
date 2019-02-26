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

        $members = $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups' => $groups, 'confirmation' => true));
        $nbrmembers = count($members);

        $x = $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups' => $groups, 'confirmation' => false));
        $nbrdemande = count($x);

        $membress = $em->getRepository("GroupBundle:Groups")
            ->findDemandeGroup($groups->getId());
        return $this->render('groups/demande.html.twig', array('demandes' => $membress,
            'group' => $groups, 'nbrdemande' => $nbrdemande, 'members' => $nbrmembers
        ));

    }

    public function MemberGroupAction(Request $request, groups $groups)
    {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups' => $groups, 'confirmation' => true));
        $nbrmembers = count($members);

        $x = $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups' => $groups, 'confirmation' => false));
        $nbrdemande = count($x);

        return $this->render('groups/members.html.twig', array(
            'group' => $groups, 'nbrdemande' => $nbrdemande, 'nbrmembers' => $nbrmembers, 'members' => $members
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