<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render('@User/Backprofil.html.twig', array(
           'curr_user' => $u
        ));

    }

    public function parameterAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //----------------
        $form = $this->createFormBuilder($u)
            ->add('Ajouter', SubmitType::class)
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

            $user->setImageuser("author-page.jpg");
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

}
