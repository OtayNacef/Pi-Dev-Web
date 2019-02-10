<?php

namespace GroupBundle\Controller;

use GroupBundle\Entity\GroupsMembers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GroupBundle\Entity\Groups;
class MemberController extends Controller
{
    public function RegoindreAction( $id)
    {

        $user = $this->getUser();
        $idu = $user->getId();
        $enman = $this->getDoctrine()->getManager();

        $ide = intval($id);

        $club = $enman->getRepository('GroupBundle:Groups')->find($ide);
        $existe = $enman->getRepository('GroupBundle:GroupsMembers')->findBy(array('user' => $user, 'club' => $ide));
        if ($existe == null) {

            $enman->persist($club);

            $enman->persist($club);

            $participe = new GroupsMembers();
            $participe->setClub($club);
            $participe->setUser($user);
            $participe->setConfirmation(0);
            $participe->setdateInscri(new \DateTime('now'));
            $enman->persist($participe);
            $enman->flush();
            return $this->redirectToRoute('groups_index');


        }else
        {
            return $this->redirectToRoute('groups_index');
        }
    }
    public function annulerINSCRIAction($id)
    {

        $user = $this->getUser();
        $idu=$user->getId();
        $enman=$this->getDoctrine()->getManager();
        $inscription = $enman->getRepository('GroupBundle:GroupsMembers')->findOneBy(array('user'=>$idu,'club'=>$id,'confirmation'=>false));
        $enman->remove($inscription);
        $enman->flush();

        return $this->redirectToRoute('groups_index');

    }

}