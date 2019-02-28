<?php
/**
 * Created by PhpStorm.
 * User: Magiko
 * Date: 27/02/2019
 * Time: 09:45
 */

namespace RelationBundle\Controller;

use Mgilet\NotificationBundle\Entity\Notification;
use RelationBundle\Entity\Demande;
use RelationBundle\Entity\Recherche;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class RechercheController extends Controller
{
    public function rechercheAction()
    {
        return $this->render('@Relation/Default/recherche.html.twig');
    }

    public function resultatAction(Request $request)
    {

        $manager = $this->getDoctrine()->getManager();
        $genre = $request->get("gender");
        $age = $request->get("age");
        if ($age == null) {
            $age[0] = 18;
            $age[1] = 60;
        }
        $religion = $request->get("religion");
        $pays = $request->get("pays");
        $ville = $request->get("ville");
        $region = $request->get("region");
        $films = $request->get("films");
        $series = $request->get("series");
        $livres = $request->get("livres");
        $musiques = $request->get("musiques");
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->saveRecherche($u, $genre, $age[0], $age[1], $religion, $pays, $ville, $region, $films, $series, $livres, $musiques);
        $datemin = new \DateTime("now -$age[0] year");
        $datemax = new \DateTime("now -$age[1] year");
        $userList = $manager->getRepository("UserBundle:User")->resultusers($u->getId(), $datemin->format("Y-m-d"), $datemax->format("Y-m-d"), $genre, $religion, $pays, $ville, $region, $films, $series, $livres, $musiques);


        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));

        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($userList, null, array('attributes' => array('id', 'nom', 'prenom', 'image', 'pays', 'ville')));
        return new JsonResponse($data);


    }

    private function saveRecherche($u, $genre, $agemin, $agemax, $religion, $pays, $ville, $region, $films, $series, $livres, $musiques)
    {
        $manager = $this->getDoctrine()->getManager();
        $interet = "";
        if ($films != null)
            $interet = $interet . ',' . implode(",", $films);
        if ($series != null)
            $interet = $interet . ',' . implode(",", $series);
        if ($livres != null)
            $interet = $interet . ',' . implode(",", $livres);
        if ($musiques != null)
            $interet = $interet . ',' . implode(",", $musiques);
        if ($religion != null)
            $religion = implode(",", $religion);
        if ($pays != null)
            $pays = implode(",", $pays);
        if ($ville != null)
            $ville = implode(",", $ville);
        if ($region)
            $region = implode(",", $region);

        $recherche = new Recherche();
        $recherche->setUser($u);
        $recherche->setDate(new \DateTime("now"));
        $recherche->setGenre($genre);
        $recherche->setAgeMin($agemin);
        $recherche->setAgeMax($agemax);
        $recherche->setRelegion($religion);
        $recherche->setPays($pays);
        $recherche->setVille($ville);
        $recherche->setRegion($region);
        $recherche->setInteret($interet);
        $manager->persist($recherche);
        $manager->flush();
    }

}