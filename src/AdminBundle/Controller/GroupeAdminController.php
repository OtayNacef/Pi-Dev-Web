<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupeAdminController extends Controller
{
    public function afficherGroupeAction()
    {

        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository("GroupBundle:Groups")->findAll();
        $signal = $em->getRepository("GroupBundle:Signalgroup")->findAll();

//
        return $this->render('@Admin/Groupe/affiche.html.twig', array("groupe" => $groupe));

    }

    public function supprimerGroupeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository("GroupBundle:Groups")->find($id);
        $em->remove($groupe);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_groupe");
    }

    public function affichersignalAction()
    {

        $em = $this->getDoctrine()->getManager();

        $signal = $em->getRepository("GroupBundle:Signalgroup")->findAll();
        $groupe = $em->getRepository("GroupBundle:Groups")->findAll();


//
        return $this->render('@Admin/Groupe/afficheSignal.html.twig', array("signal" => $signal, "groupe" => $groupe));

    }

    public function supprimerSignalAction($id, $id2)
    {
        $em = $this->getDoctrine()->getManager();
        $signal = $em->getRepository("GroupBundle:Signalgroup")->find($id);
        $groupe = $em->getRepository("GroupBundle:Groups")->find($id2);

        $groupe->setNbrSignal($groupe->getNbrSignal() - 1);
        $em->remove($signal);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_signal");
    }
    public function rechercheGroupeAction(Request $request){
        $nom=$request->get("nom");
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository("GroupBundle:Groups")->groupeRecherche($nom);
        return new Response($this->renderView('@Admin/Groupe/RechAjax.html.twig', array('groupe' => $groupe)));
    }


}
