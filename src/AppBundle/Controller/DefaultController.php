<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    public function indexAction()
    {
//        // replace this example code with whatever you need
//        return $this->render('default/choixbonplan.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
//        ]);

        /** @var  \FOS\UserBundle\Form\Factory\FactoryInterface $formFactory*/
        $formFactory = $this->container->get('fos_user.registration.form.factory');

        $form = $formFactory->createForm();

        return $this->container->get('templating')->renderResponse('default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
