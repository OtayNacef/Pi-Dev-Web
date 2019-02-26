<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    public function indexAction()
    {


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT B From HotesBundle:MaisonsHotes B order by B.nom desc ')->setMaxResults(3);
        $hotes = $query->getResult();
        $query = $em->createQuery('SELECT B From ShopBundle:Produit B order by B.date ')->setMaxResults(5);
        $shop = $query->getResult();
        /** @var  \FOS\UserBundle\Form\Factory\FactoryInterface $formFactory*/
        $formFactory = $this->container->get('fos_user.registration.form.factory');

        $form = $formFactory->createForm();

        return $this->container->get('templating')->renderResponse('default/index.html.twig', array(
            'form' => $form->createView(),
            'hotes' => $hotes,
            'shop' => $shop
        ));
    }
}
