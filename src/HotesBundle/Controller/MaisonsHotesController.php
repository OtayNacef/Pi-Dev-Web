<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\CommentaireHote;
use HotesBundle\Entity\DemandeResponsableHote;
use HotesBundle\Entity\MaisonsHotes;
use HotesBundle\Entity\ReservationHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

//afficher maison d'hote
// /hotes/maisonshotes/
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $maisonsHotes = $em->getRepository('HotesBundle:MaisonsHotes')->findAll();
        $maisonsHote = new MaisonsHotes();
        //les 5 maisons d'hotes
        $query = $em->createQuery('SELECT B From HotesBundle:MaisonsHotes B order by B.nom desc ')->setMaxResults(3);
        $hotes = $query->getResult();
        //pagination
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $maisonsHotes, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        //ajout
        $form = $this->createForm('HotesBundle\Form\MaisonsHotesType', $maisonsHote);
        $form_filter = $this->createForm('HotesBundle\Form\PaysSearchType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //****************************************************
            $file = $maisonsHote->getImage();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $maisonsHote->setImage($fileName);
            //******************************************** end upload img
            $em = $this->getDoctrine()->getManager();
            $maisonsHote->setUser($u);
            $em->persist($maisonsHote);
            $em->flush();
            return $this->redirectToRoute('maisonshotes_index');
        }
        /****** Envoyer Demande Responsable**************/
        $demade_resp = new DemandeResponsableHote();
        if ($request->isMethod("POST")) {
            $demade_resp->setUser($u);
            $demade_resp->setDescription($request->get('description'));
            $demade_resp->setDate(new \DateTime());
            $em->persist($demade_resp);
            $em->flush();
            return $this->redirectToRoute('maisonshotes_index');

        }
        /****************************************************/


        return $this->render('maisonshotes/index.html.twig', array(
            'maisonsHotes' => $pagination,
            'form' => $form->createView(),
            'form_filter' => $form_filter->createView(),
            'hote' => $hotes


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
    // /hotes/maisonshotes/show/{id}

    public function showAction(Request $request, MaisonsHotes $maisonsHote)
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
        $query = $em->createQuery('SELECT B From HotesBundle:MaisonsHotes B order by B.pays desc ')->setMaxResults(5);
        $hotes = $query->getResult();

        return $this->render('maisonshotes/show.html.twig',
            array(
                'maisonsHote' => $maisonsHote,
                'delete_form' => $deleteForm->createView(),
                'edit_form' => $editForm->createView(),
                'hote' => $hotes,
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
        $am = $this->getDoctrine()->getManager();
        $hote = $am->getRepository("HotesBundle:MaisonsHotes")->find($id);
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
            ->getForm();
    }


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
    public function deletCommentAction($id, $idm)
    {
        $am = $this->getDoctrine()->getManager();
        $maison = $am->getRepository("HotesBundle:MaisonsHotes")->find($idm);
        $comment = $am->getRepository("HotesBundle:CommentaireHote")->find($id);
        $am->remove($comment);
        $am->flush();
        return $this->redirectToRoute('maisonshotes_show', array('id' => $maison->getId()));
    }

    //********** Commentaire update *********//
    public function updateCommentAction(Request $request, $id)
    {
        $comment = $this->getDoctrine()
            ->getRepository('HotesBundle:CommentaireHote')
            ->find($id);
        $comment->setContent($comment->getContent());
        $form = $this->createFormBuilder($comment)
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('content', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'create todo', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $content = $form['content']->getData();
            $now = new\DateTime('now');
            $sn = $this->getDoctrine()->getManager();
            $comment = $sn
                ->getRepository('HotesBundle:CommentaireHote')
                ->find($id);
            $comment->setContent($content);
            $comment->setPublishdate($now);
            $sn->flush();
            $this->addFlash(
                'notice',
                'todo updated'
            );
            return $this->redirectToRoute('maisonshotes_show');
        }
        return $this->render('@Hotes/Default/comm.html.twig', array(
            'form' => $form->createView()
        ));

    }

    public function ModifierCommentaireAction($idc, Request $request)
    {
        $am = $this->getDoctrine()->getManager();
        $comment = $am->getRepository("HotesBundle:CommentaireHote")->find($idc);
        if ($request->isMethod("POST")) {
            $comment->setContent($request->get('content-comment'));
            $am->flush();
        }
        return $this->render('maisonshotes/show.html.twig', array('c' => $comment));

    }


}
