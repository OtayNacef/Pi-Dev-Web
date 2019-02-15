<?php

namespace BonPlansBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BonPlansBundle:Default:choixbonplan.html.twig');
    }
}
