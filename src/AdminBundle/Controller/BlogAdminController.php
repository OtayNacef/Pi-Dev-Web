<?php

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BlogAdminController extends Controller
{


    public function AfficheBlogAction()
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository("UserBundle:Blog")->findAll();
        return $this->render('@Admin/Blog-User/listBlog.html.twig', array('blog' => $blog));
    }

    public function listuserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findAll();
        return $this->render('@Admin/Blog-User/listuser.html.twig', array('users' => $user));
    }

    public function listreclamationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository('UserBundle:Signaler')->findAll();
        return $this->render('@Admin/Blog-User/Reclamation.html.twig', array('signaler' => $reclamation));
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
        return $this->render('@Admin/Blog-User/listuser.html.twig', array("users" => $users));

    }
}
