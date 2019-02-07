<?php
/**
 * Created by PhpStorm.
 * User: Magiko
 * Date: 01/02/2019
 * Time: 14:54
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Album;
use UserBundle\Entity\Blog;
use UserBundle\Entity\CentreInteret;
use UserBundle\Entity\Publication;
use UserBundle\Entity\Signaler;
use UserBundle\Entity\User;

class CompteController extends Controller
{

    public function indexAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'livre'));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $id),null,9,null);
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $id),array('datePublication' => 'DESC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("Compte_homepage", array('id' => $lastautreuser[0]->getId()));
            }
        }
        return $this->render('@User/Compte.html.twig', array(
            'autreUser' => $u[0],'films'=>$films,'series'=>$series,'artists'=>$artists,'livres'=>$livres,
            'photos'=>$photos,'pubs'=>$pubs
        ));
    }

    public function autreAlbumAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $id), array('datePublication' => 'ASC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("autre_profil_album", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //-----------------------------------------------
        return $this->render('@User/autrealbum.html.twig', array(
            'autreUser' => $u[0], 'photos' => $photos
        ));
    }

    public function autreBlogAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $post = $em->getRepository(Blog::class)->findBy(array('author' => $id), array('dateCreation' => 'ASC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("Compte_blog", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //-----------------------------------------------
        return $this->render('@User/autreBlog.html.twig', array(
            'autreUser' => $u[0], 'post' => $post
        ));
    }

    public function autreAproposAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id, 'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id, 'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id, 'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id, 'type' => 'livre'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("Compte_apropos", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //-----------------------------------------------
        return $this->render('@User/autreDescription.html.twig', array(
            'autreUser' => $u[0], 'films' => $films, 'series' => $series, 'artists' => $artists, 'livres' => $livres
        ));
    }
}