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

        $pubs = $em->getRepository(Publication::class)->findAll();
        $user = $this->getUser();
        $post = $em->getRepository('UserBundle:Publication')->findOneById($request->get('id'));

        if ($request->isMethod('post')) {

            $comment = new PubComment();
            $comment->setUser($user);
            $comment->setPub($post);
            $comment->setContent($request->get('comment-content'));
            $comment->setPublishdate(new \DateTime('now'));
            $post->setRepliesnumber($post->getRepliesnumber() + 1);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('relation_homepage');
        }
        $comments = $em->getRepository('UserBundle:PubComment')->findByPub($post);

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT V From UserBundle:User V order by V.date_naissance desc ')->setMaxResults(3);
        $sug = $query->getResult();
        return $this->render('default/home.html.twig', array(
            'sug' => $sug,
            'pubs' => $pubs,
            'curr_user' => $user,
            'comments' => $comments

        ));
    }
}