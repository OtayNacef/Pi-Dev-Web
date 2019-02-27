<?php

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BlogAdminController extends Controller
{


    public function AfficheBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$blog = $em->getRepository("UserBundle:Blog")->findAll();
        $blog = $em->createQuery('SELECT V From UserBundle:Blog V order by V.dateCreation desc ');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $blog,
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        return $this->render('@Admin/Blog-User/listBlog.html.twig', array('blog' => $pagination));
    }

    public function listuserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $user,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return $this->render('@Admin/Blog-User/listuser.html.twig', array('users' => $pagination));
    }

    public function listreclamationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository('UserBundle:Signaler')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $reclamation,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return $this->render('@Admin/Blog-User/Reclamation.html.twig', array('signaler' => $pagination));
    }

    public function userdeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $findUser = $em->getRepository('UserBundle:User')->find($id);
        $a = $findUser->isEnabled();
        if ($a == false) {
            $findUser->setEnabled(true);
        } else {
            $findUser->setEnabled(false);
        }
        $em->flush();
        return $this->redirectToRoute('admin_list_user');
    }

    public function supprimerCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute("admin_affiche_categorie");
    }

    public function rechercheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->get('filter');
        $users = $em->getRepository('UserBundle:User')->findBy(array('username' => $key));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        return $this->render('@Admin/Blog-User/listuser.html.twig', array("users" => $pagination));

    }

    public function deletBlogAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $blog = $am->getRepository("UserBundle:Blog")->find($id);
        $am->remove($blog);
        $am->flush();
        return $this->redirectToRoute("admin_list_blog");
    }
}
