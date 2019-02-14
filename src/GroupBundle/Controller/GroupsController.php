<?php

namespace GroupBundle\Controller;

use GroupBundle\Entity\Groups;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Blog;
use UserBundle\Entity\PublicationGroup;

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
        $group = new Groups();
        $form = $this->createForm('GroupBundle\Form\GroupsType', $group);
        $form->handleRequest($request);
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
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


        if ($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p = $em->getRepository(Groups::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("groups_index");
            }

            return $this->redirectToRoute('groups_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $group->setOwner($u);
            $group->setDateDeCreation(new \DateTime());
            $group->setNbrMembre(1);
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }
        return $this->render('groups/index.html.twig', array(
            'u'=>$u,
            'groups' => $groups,
            'form' => $form->createView()
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
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a group entity.
     *
     */
    public function showAction(Request $request,Groups $group)
    {
        $em = $this->getDoctrine()->getManager();

        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        $pubs = $em->getRepository(PublicationGroup::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'DESC'));

        if ($request->isMethod('POST')) {
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
            if ($request->request->has('contenuajout')) {
                $p = new PublicationGroup();
                $p->setContenu(($request->get('contenuajout')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $p->setUser($u);
                $em->persist($p);
                $em->flush();
            }
            return $this->redirectToRoute('groups_show', array('id' => $group->getId()))    ;
        }


        $deleteForm = $this->createDeleteForm($group);




        return $this->render('groups/group.html.twig', array(
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
            'iduser' => $u->getId(),'curr_user' => $u,'pubs'=>$pubs
        ));
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
    public function deleteAction(Request $request, Groups $group)
    {
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

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






}
