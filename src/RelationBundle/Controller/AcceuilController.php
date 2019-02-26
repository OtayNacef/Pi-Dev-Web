<?php
/**
 * Created by PhpStorm.
 * User: Magiko
 * Date: 24/02/2019
 * Time: 17:08
 */

namespace RelationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\PubComment;
use UserBundle\Entity\Publication;

class AcceuilController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        $pubs = $em->getRepository(Publication::class)->findBy(array(), array('datePublication' => 'DESC'));
        $user = $this->getUser();
        $post = $em->getRepository('UserBundle:Publication')->findBy(array('id' => $request->get('idp')));

        if ($request->isMethod('post')) {
            if ($request->request->has('comment-content')) {
                $comment = new PubComment();
                $comment->setUser($user);
                $comment->setPub($post[0]);
                $comment->setContent($request->get('comment-content'));
                $comment->setPublishdate(new \DateTime('now'));
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('user_profil');
            }
        }
        if ($request->isMethod('POST')) {
            if ($request->request->has('idpubd')) {
                $p = $em->getRepository(Publication::class)->find($request->get("idpubd"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("user_profil");
            }
            if ($request->request->has('idpubmodal')) {
                $p = $em->getRepository(Publication::class)->find($request->get("idpubmodal"));
                $p->setContenu(($request->get('contenuup')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $em->persist($p);
                $em->flush();

                return $this->redirectToRoute("user_profil");
            }

            if ($request->request->has('contenuajout')) {
                $p = new Publication();
                $p->setContenu(($request->get('contenuajout')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $p->setUser($u);
                $em->persist($p);
                $em->flush();

            }
            return $this->redirectToRoute('user_profil');

        }
        $comments = $em->getRepository('UserBundle:PubComment')->findByPub($post);
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->findAll();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT V From UserBundle:User V order by V.date_naissance desc ')->setMaxResults(3);
        $sug = $query->getResult();
        return $this->render('default/home.html.twig', array(
            'sug' => $sug,
            'pubs' => $pubs,
            'curr_user' => $user,
            'comments' => $comments,
            'bonplans' => $bonplan
        ));


    }
}