<?php

namespace HotesBundle\Controller;

use HotesBundle\Entity\MaisonsHotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    function AfficherAction()
    {
        $am = $this->getDoctrine()->getManager();
        $hote = $am->getRepository("HotesBundle:MaisonsHotes")->findAll();
        return $this->render("@Hotes\hotes\afficheHote.html.twig", array('hote' => $hote));
    }

    function AjouterHoteAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();

        $am = $this->getDoctrine()->getManager();
        $hote = new MaisonsHotes();
        if ($request->isMethod("POST")) {
            $hote->setNom($request->get('nom'));
            $hote->setDescription($request->get('description'));
            $hote->setPays($request->get('pays'));
            $hote->setCapacites($request->get('capacite'));
            $hote->setTel($request->get('tel'));
            $hote->setMail($request->get('mail'));
            $hote->setImage($request->get('image'));
            $hote->setGouvernorat($request->get('gouvernorat'));
            $hote->setSiteWeb($request->get('site'));
            $hote->setAdresse($request->get('adresse'));
            $hote->setUser($u);

            $am->persist($hote);
            $am->flush();

        }
        return $this->render("@Hotes\hotes\ajouterHote.html.twig");
    }


}
