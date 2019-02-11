<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use RelationBundle\Entity\Demande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Album;
use UserBundle\Entity\CentreInteret;
use UserBundle\Entity\Publication;
use UserBundle\Entity\Signaler;
use UserBundle\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Mgilet\NotificationBundle\Entity\Notification;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getManager();
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'DESC'));
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $u->getId()),null,9,null);




        if ($request->isMethod('POST')) {
            if ($request->request->has('idpubd')) {
                $p= $em->getRepository(Publication::class)->find($request->get("idpubd"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("user_profil");
            }
            if ($request->request->has('idpubmodal')) {
                $p= $em->getRepository(Publication::class)->find($request->get("idpubmodal"));
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
        return $this->render('@User/Backprofil.html.twig', array(
            'iduser' => $u->getId(),'curr_user' => $u,'pubs'=>$pubs,'films'=>$films,'series'=>$series,'artists'=>$artists,'livres'=>$livres,
            'photos' => $photos
        ));

    }

    public function parameterAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
//        ------------ Ajouter image User
        $form=$this->createFormBuilder($u)
            ->add('imageFile', VichImageType::class)
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid())) {
            $u = $form->getData();
            $em->flush();
            return $this->redirectToRoute('user_parameter');
        }
        //----------------
        $user = $em->getRepository(User::class)->find($u->getId());
        if ($request->isMethod('POST')) {
            //Mettre a jour
            $user->setNom($request->get('nom'));
            $user->setPrenom($request->get('prenom'));
            $user->setGender($request->get('gen'));
            $d = new \DateTime($request->get('datetimepicker'));
            $user->setDateNaissance($d);
            $user->setPays($request->get('pays'));
            $user->setVille($request->get('ville'));
            $user->setRegion($request->get('region'));
            $user->setTel($request->get('tel'));
            $user->setRelegion($request->get('rel'));
            $user->setFacebook($request->get('facebook'));
            $user->setTwitter($request->get('twitter'));
            $user->setInstagram($request->get('instagram'));
            $user->setDescription($request->get('description'));

          //  $user->setImageFile("author-page.jpg");
            //----------------------PhotoUpload

            //--------------------------------
            $em->persist($user);
            $em->flush();
            //Rederiger vers read
            return $this->redirectToRoute('user_parameter');
        }

        return $this->render('@User/parameter.html.twig', array(
            'iduser' => $u->getId(), 'us' => $u, 'form' => $form->createView()
        ));
    }

    public function descriptionAction()
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        return $this->render('@User/description.html.twig', array(
            'iduser' => $u->getId(),'curr_user' => $u,'films'=>$films,'series'=>$series,'artists'=>$artists,'livres'=>$livres
        ));
    }
    public function albumAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $album = new Album();
        //----------------
        $form=$this->createFormBuilder($album)
            ->add('imageFile', VichImageType::class)
            ->add('user', HiddenType::class, array('data' => $u))
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted())&&($form->isValid()))
        {
            $album=$form->getData();
            $album->setUser($u);
            $em->persist($album);
            $em->flush();
            return $this->redirectToRoute('user_album');
        }
        //-------------------supprimer photo
        if ($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p= $em->getRepository(Album::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("user_album");
            }
            return $this->redirectToRoute('user_album');
        }
        //----------------------------------
        $photos=$em->getRepository(Album::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'ASC'));

        return $this->render('@User/album.html.twig', array(
            'curr_user' => $u,'form'=>$form->createView(),'photos'=>$photos
        ));
    }
    public function centreinteretAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        //-----------------Afficher les centres d'interet
        $em = $this->getDoctrine()->getManager();
        $cen_user_film = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $cen_user_serie = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $cen_user_livre = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        $cen_user_artist = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));

        $newc = new CentreInteret();
        //$newl = new Loisir();
        if ($request->isMethod('POST'))
        {
            if ($request->request->has('idc')) {
                $delc= $em->getRepository(CentreInteret::class)->find($request->get("idc"));
                $em->remove($delc);
                $em->flush();
                return $this->redirectToRoute("centreinteret");
            }
            if ($request->request->has('newcentrefilm'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentrefilm"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentreserie'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentreserie"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentrelivre'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentrelivre"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentreartist'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentreartist"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }

        }

        //-----------------
        return $this->render('@User/centreinteret.html.twig', array(
            'iduser' => $u->getId(),'centresfilm' => $cen_user_film,'centresserie' => $cen_user_serie,
            'centreslivre'=>$cen_user_livre,'centreartist'=>$cen_user_artist
        ));
    }

    public function demandeAutreAction($id)
    {
        $cuser = $this->container->get('security.token_storage')->getToken()->getUser();
        $notificationManager = $this->get('mgilet.notification');
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("UserBundle:User")->find($id);
        $demande = new Demande();
        $demande->setSender($cuser);
        $demande->setReceiver($user);
        $demande->setDateDemande(new \DateTime("now"));
        $manager->persist($demande);
        $manager->flush();
        $notification = new Notification();
        $notification->setSubject("Demande");
        $notification->setLink($cuser->getId());
        $notification->setDate(new \DateTime("now"));
        $notification->setMessage($demande->getId());
        $notificationManager->addNotification(array($user), $notification, true);
        $this->redirectToRoute(Compte_homepage);
        return new JsonResponse("OK");
    }

}
