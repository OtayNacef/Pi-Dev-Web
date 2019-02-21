<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminHoteController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/Default/Hotes.html.twig');
    }
}
