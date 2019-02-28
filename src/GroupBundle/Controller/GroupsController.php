<?php

namespace GroupBundle\Controller;

use Doctrine\ORM\Mapping\Id;
use GroupBundle\Entity\Comments;
use GroupBundle\Entity\GroupeImage;
use GroupBundle\Entity\Groups;
use GroupBundle\Entity\GroupsMembers;
use GroupBundle\Entity\PublicationGroup;
use GroupBundle\Entity\Signal;
use GroupBundle\Entity\Signalgroup;
use GroupBundle\Form\SignalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Blog;


/**
 * Group controller.
 *
 */
class GroupsController extends Controller
{
    /**
     * Lists all group entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('GroupBundle:Groups')->findAll();
        $en= $this->get('doctrine.orm.entity_manager');
        $dql="SELECT g FROM GroupBundle:Groups g ";
        $query= $en->createQuery($dql);
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
       $te=$em->getRepository(GroupsMembers::class)->findAll();
        $groupedit = $em->getRepository(Groups::class)->findAll();
        if ($request->isMethod('POST')) {
            if ($request->request->has('ids')) {
                $p = $em->getRepository(Groups::class)->find($request->get("ids"));
                //Mettre a jour
                $p->setNom($request->get('nom'));
                $p->setDescription($request->get('description'));
                $em->persist($groupedit);
                $em->flush();
                //Rederiger vers read
                return $this->redirectToRoute('groups_index');
            }
        }
        $group = new Groups();
        $form = $this->createForm('GroupBundle\Form\GroupsType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $group->setOwner($u);
            $group->setDateDeCreation(new \DateTime('now'));
            $group->setNbrMembre(1);
            $em->persist($group);
            $em->flush();
        }


        if ($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p = $em->getRepository(Groups::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("groups_index");
            }

            return $this->redirectToRoute('groups_index');
        }
        $test1= $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups'=>$group,'confirmation' => true));
        $paginator =$this->get('knp_paginator');
        $pagination = $paginator->paginate(
          $query,$request->query->getInt('page',1)
        ,3
        );
        return $this->render('groups/index.html.twig', array(
            'u'=>$u,
            'groups' => $pagination,
            'form' => $form->createView(),
            'test'=>$test1
        ));
    }

    /**
     * Creates a new group entity.
     *
     */
    public function newAction(Request $request)
    {
        $group = new Groups();
        $form = $this->createForm('GroupBundle\Form\GroupsType', $group);
        $form->handleRequest($request);
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $group->setOwner($u);
            $group->setDateDeCreation(new \DateTime());
            $group->setNbrMembre(1);
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }

        return $this->render('groups/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a group entity.
     *
     */
    public function showAction(Request $request,Groups $group)
    {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('GroupBundle:GroupsMembers')->findBy(array('groups'=>$group,'confirmation' => true));
        $nbrmembers= count($members);

        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $test = $em->getRepository('GroupBundle:GroupsMembers')->findOneBy(array('user' => $u, 'groups' => $group, 'confirmation' => false));


        $demande = $em->getRepository('GroupBundle:GroupsMembers')->findDemandeGroup($group);
        $nbrdemande = count($demande);


        $editForm = $this->createForm('GroupBundle\Form\GroupsType', $group);
        $editForm->handleRequest($request);
        $signal = new Signalgroup();
        $form1 = $this->createForm('GroupBundle\Form\SignalgroupType', $signal);
        $form1->handleRequest($request);


        if ($form1->isSubmitted() && $form1->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $signal->setOwner($u);
            $signal->setGroup($group);
            $group->setNbrSignal($group->getNbrSignal()+1);
            $em->persist($signal);
            $em->flush();
            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));

        }
        $im = new GroupeImage();
        $form2 = $this->createForm('GroupBundle\Form\GroupeImageType', $im);
        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $im->setOwner($u);
            $im->setDatePublication(new \DateTime('now'));
            $im->setGroup($group);
            $em->persist($im);
            $em->flush();
            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));

        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }


        $pubs = $em->getRepository(PublicationGroup::class)->findBy(array('groups' => $group->getId()),
            array('datePublication' => 'DESC'));
        $images = $em->getRepository(GroupeImage::class)->findByGroup($group);
        $us = $this->getUser();
        $post = $em->getRepository('GroupBundle:PublicationGroup')->findBy(array('id' => $request->get('idp')));
        if ($request->isMethod('post')) {
            if ($request->request->has('comment-content')) {
                $comment = new Comments();
                $comment->setOwner($us);
                $comment->setPub($post[0]);
                $comment->setContent($request->get('comment-content'));
                $comment->setPublishdate(new \DateTime('now'));
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
            }
//            if ($request->request->has('idcom')) {
//                $c= $em->getRepository(Comments::class)->find($request->get("idcom"));
//                $em->remove($c);
//                $em->flush();
//                return $this->redirectToRoute('groups_show', array('id' => $group->getId()))    ;
//            }
        }


        if ($request->isMethod('POST')) {
            if ($request->request->has('contenuajout')) {
                $p = new PublicationGroup();
                $p->setContenu(($request->get('contenuajout')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $p->setUser($u);
                $p->setGroups($group);
                $em->persist($p);
                $em->flush();
            }
            if ($request->request->has('idpubd')) {
                $p= $em->getRepository(PublicationGroup::class)->find($request->get("idpubd"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute('groups_show', array('id' => $group->getId()))    ;
            }
            if ($request->request->has('idpubmodal')) {
                $p= $em->getRepository(PublicationGroup::class)->find($request->get("idpubmodal"));
                $p->setContenu(($request->get('contenuup')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $em->persist($p);
                $em->flush();
                return $this->redirectToRoute('groups_show', array('id' => $group->getId()))    ;
            }

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()))    ;
        }



        $deleteForm = $this->createDeleteForm($group);



        return $this->render('groups/group.html.twig', array(
            'image'=>$images,
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'iduser' => $u->getId(), 'curr_user' => $u, 'pubs' => $pubs, 'nbrdemande' => $nbrdemande,'test'=> $test
        ,'nbrmembers'=>$nbrmembers
        ));
    }
    public function supprimerImageAction($id,$id2)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository("GroupBundle:GroupeImage")->find($id);
        $groupe = $em->getRepository("GroupBundle:Groups")->find($id2);
        $em->remove($image);
        $em->flush();
        return $this->redirectToRoute('groups_show', array('id' => $groupe->getId()))    ;



    }
    public function supprimerCommentaireAction($id,$id2)
    {
        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository("GroupBundle:Comments")->find($id);
        $groupe = $em->getRepository("GroupBundle:Groups")->find($id2);
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute('groups_show', array('id' => $groupe->getId()))    ;



    }

    public function CommentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('GroupBundle:Comments')
            ->getCommentsForBlog($id);
        return $this->render('groups/comments.html.twig', array('comments' => $comments));
    }


    /**
     * Displays a form to edit an existing group entity.
     *
     */
    public function editAction(Request $request, Groups $group)
    {
        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm('GroupBundle\Form\GroupsType', $group);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups_edit', array('id' => $group->getId()));
        }


        return $this->render('groups/edit.html.twig', array(
            'group' => $group,
            'edit_form' => $editForm->createView(),

            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a group entity.
     *
     */
    public function deleteAction(Groups $group)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();


        return $this->redirectToRoute('groups_index');
    }

    /**
     * Creates a form to delete a group entity.
     *
     * @param Groups $group The group entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groups $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groups_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository('GroupBundle:Groups')->findEntitiesByString($requestString);
        if(!$entities) {
            $result['entities']['error'] = "pas de groupe avec ce nom";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($entities){
        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = [$entity->getNom(),$entity->getImage()];
        }
        return $realEntities;
    }









}
