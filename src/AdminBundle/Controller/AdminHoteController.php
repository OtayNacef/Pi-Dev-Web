<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminHoteController extends Controller
{
    public function rechercheAjaxAction(Request $request)
    {
        $n = $request->get("n");
        $em = $this->getDoctrine()->getManager();
        $maison = $em->getRepository("HotesBundle:MaisonsHotes")->ajaxRecherche($n);
        return new Response($this->renderView('@Admin/default/maisonAjax.html.twig', array('hote' => $maison)));
    }

    public function AfficheListesHotesAction(Request $request)

    {
        // ***************************   Liste des maisons d'hotes **************************//
        $em = $this->getDoctrine()->getManager();
        $hotes = $em->getRepository("HotesBundle:MaisonsHotes")->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $hotes, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        // ***************************   Liste des demande responsable d'hotes **************************//
        $hotes_responsable = $em->getRepository("HotesBundle:DemandeResponsableHote")->findAll();
        $pagination_responsable = $paginator->paginate(
            $hotes_responsable, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('@Admin/Default/HotesAdmin.html.twig', array("hote" => $pagination,
            "hotes_responsable" => $pagination_responsable));

    }

    /*****************     Supprimer des maisons d'hote par l'admin         *******************/
    public function deleteAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $hote = $am->getRepository("HotesBundle:MaisonsHotes")->find($id);
        $am->remove($hote);
        $am->flush();
        return $this->redirectToRoute("admin_homepage_hote");
    }

    /***************************** Supprimer une demande responsable *********************/

    public function SupprimerDemandeAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $hote_respo = $am->getRepository("HotesBundle:DemandeResponsableHote")->find($id);
        $am->remove($hote_respo);
        $am->flush();
        return $this->redirectToRoute("admin_homepage_hote");
    }

    /******************************* Ajouter Role Responnsable *****************************/
    public function ResposableAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $user = $sn->getRepository('UserBundle:User')->find($id);
        $user->setRoles(array('ROLE_RESPONSABLE_HOTE'));
        $demande = $sn->getRepository('HotesBundle:DemandeResponsableHote')->find($id);
        $sn->remove($demande);
        // $user->setRoles(2);
        $sn->flush();

        $this->addFlash(
            'notice',
            'Utilisateur  est devenu responsable'
        );

        return $this->redirectToRoute('admin_homepage_hote');

    }

}
