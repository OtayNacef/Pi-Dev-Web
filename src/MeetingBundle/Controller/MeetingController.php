<?php

namespace MeetingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MeetingController extends Controller
{
    public function indexAction()
    {
        return $this->render('MeetingBundle:Default:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('MeetingBundle:Default:addmeeting.html.twig');
    }
}
