<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\CommentaireHote;
use HotesBundle\Entity\MaisonsHotes;
use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maisonshote controller.
 *
 */
class MaisonsHotesController extends Controller
{
    /**
     * Lists all maisonsHote entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $maisonsHotes = $em->getRepository('HotesBundle:MaisonsHotes')->findAll();
        $maisonsHote = new MaisonsHotes();
        //ajout
        $form = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $form_filter = $this->createForm('HotesBundle\Form\PaysSearchType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $maisonsHote->setUser($u);
            $em->persist($maisonsHote);
            $em->flush();
            return $this->redirectToRoute('maisonshotes_index');
        }


        return $this->render('maisonshotes/index.html.twig', array(
            'maisonsHotes' => $maisonsHotes,
            'form' => $form->createView(),
            'form_filter'=>$form_filter->createView()
        ));
    }


    /**
     * Creates a new maisonsHote entity.
     *
     */
    public function newAction(Request $request)
    {
        $maisonsHote = new MaisonsHotes();
        $form = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($maisonsHote);
            $em->flush();

            return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
        }

        return $this->render('maisonshotes/new.html.twig', array(
            'maisonsHote' => $maisonsHote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a maisonsHote entity.
     *
     */
    public function showAction(Request $request,MaisonsHotes $maisonsHote)
    {  // $maisonsHote= new MaisonsHotes();
        $deleteForm = $this->createDeleteForm($maisonsHote);
        $editForm = $this->createForm('HotesBundle\Form\HotesUpdateType', $maisonsHote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
        }

        /**************** Commentaire ADD aHote ***********************/
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $em->getRepository('HotesBundle:MaisonsHotes')->find($maisonsHote);
        $arr = array();

        if ($request->isMethod('post')) {
        $comment = new CommentaireHote();
        $comment->setUser($user);
        $comment->setHote($post);
        $comment->setContent($request->get('comment-content'));
        $comment->setPublishdate(new \DateTime('now'));
        $post->setRepliesnumber($post->getRepliesnumber() + 1);
        $em->persist($post);
        $em->persist($comment);
        $em->flush();
        return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
    }
        $comments = $em->getRepository('HotesBundle:CommentaireHote')->findByhote($post);
        $numberofcomments = count($comments);


        /***************************************************/
        /******   Reservation   ******/
        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        $am = $this->getDoctrine()->getManager();
        $reservation = new ReservationHotes();

        if ($form = $request->isMethod("POST")) {
            $reservation->setDateDebut(new \DateTime($request->get('date_debut')));
            $reservation->setDateFin(new \DateTime($request->get('date_fin')));
            $reservation->setNbPersonne($request->get('nb_place'));
            if ($maisonsHote->getCapacites() > $reservation->getNbPersonne()) {
                $diff = $reservation->getDateFin()->diff($reservation->getDateDebut())->format("%a");
                $reservation->setUser($u);
                $prix = $maisonsHote->getPrix();
                $reservation->setPrix($prix * $diff);
            }
            $am->persist($reservation);
            $am->flush();
            return $this->redirectToRoute("maisonshotes_reservation");
        }
        /***************************************************/

        return $this->render('maisonshotes/show.html.twig',
            array(
            'maisonsHote' => $maisonsHote,
                'reservationHote' => $reservation,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
                'numberofcomments' => $numberofcomments,
                'comments' => $comments,
                'arr' => $arr

        ));

    }

    /**
     * Displays a form to edit an existing maisonsHote entity.
     *
     */
    public function editAction(Request $request, MaisonsHotes $maisonsHote)
    {
        $deleteForm = $this->createDeleteForm($maisonsHote);
        $editForm = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('maisonshotes_show', array('id' => $maisonsHote->getId()));
        }



        return $this->render('maisonshotes/edit.html.twig', array(
            'maisonsHote' => $maisonsHote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a maisonsHote entity.
     *
     */
//    public function deleteAction(Request $request, MaisonsHotes $maisonsHote)
//    {
//        $form = $this->createDeleteForm($maisonsHote);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($maisonsHote);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('maisonshotes_index');
//    }
    function deleteAction($id)
    {
        $am=$this->getDoctrine()->getManager();
        $hote=$am->getRepository("HotesBundle:MaisonsHotes")->find($id);
        $am->remove($hote);
        $am->flush();
        return $this->redirectToRoute("maisonshotes_index");

    }


    /**
     * Creates a form to delete a maisonsHote entity.
     *
     * @param MaisonsHotes $maisonsHote The maisonsHote entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MaisonsHotes $maisonsHote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('maisonshotes_delete', array('id' => $maisonsHote->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    // filtre par Pays
//public function FilterAction(Request $request)
//{
//    $em = $this->getDoctrine()->getManager();
//   $paysFiltre=$request->get("form_filter")["pays"] ;
//   $entities=$em->getRepository('HotesBundle:MaisonsHotes')->FilterByPays($paysFiltre);
//    return $this->render('@Hotes/hotes/hoterecherche.html.twig', array(
//           'maisonsHotePays' => $entities));
//
//
//}

    public function FilterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('HotesBundle:MaisonsHotes')->FilterByPays($requestString);
        if (!$entities) {
            $result['entities']['error'] = "there is no hote with this country";
        } else {
//            $nom=$entities->getNom();
//            $prenom=$entities->getPreom();
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] =
                [$entity->getUser(),
                    $entity->getNom(),
                    $entity->getDescription(),
                    $entity->getImage(),
                    $entity->getPays()];
        }
        return $realEntities;
    }

// Recherche Ajax par nom

    public function ChercherHotesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('nom_hote');
        $entities = $em->getRepository('HotesBundle:MaisonsHotes')->findEntitiesByString($requestString);

        return $this->render("@Hotes\hotes\hoterecherche.html.twig", array('hote' => $entities));


    }

    //******** Commentaire delete **************//
    public function deletCommentAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $comment = $am->getRepository("HotesBundle:CommentaireHote")->find($id);
        $am->remove($comment);
        $am->flush();
        return $this->render("@Hotes\hotes\show.html.twig", array('comment_id' => $comment));
    }

    //********** Commentaire update *********//
    public function updateCommentAction(Request $request, $id)
    {

        $am = $this->getDoctrine()->getManager();
        $comment = $am->getRepository("EspritParcBundle:Voiture")->find($id);


    }







}
