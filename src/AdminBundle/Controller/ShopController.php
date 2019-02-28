<?php

namespace AdminBundle\Controller;

use ShopBundle\Entity\Category;
use ShopBundle\Entity\Region;
use ShopBundle\Form\CategoryType;
use ShopBundle\Form\RegionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    public function indexAction(Request $request)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository("ShopBundle:Produit")->findAll();
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $produit, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                8/*limit per page*/
            );


            return $this->render('@Admin/Shop/shop.html.twig', array("shop" => $pagination)
            );

        }
    }

    public function catAction(Request $request)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository("ShopBundle:Category")->findAll();

            $categorie = new Category();
            $form = $this->createForm(CategoryType::class, $categorie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $sn = $this->getDoctrine()->getManager();
                $sn->persist($categorie);
                $sn->flush();

                return $this->redirectToRoute("cat");
            }

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $produit, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                8/*limit per page*/
            );


            return $this->render('@Admin/Shop/cat.html.twig', array("cat" => $pagination, 'form' => $form->createView())
            );

        }
    }

    public function regAction(Request $request)
    {
        {
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository("ShopBundle:Region")->findAll();

            $region = new Region();
            $form = $this->createForm(RegionType::class, $region);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $sn = $this->getDoctrine()->getManager();
                $sn->persist($region);
                $sn->flush();

                return $this->redirectToRoute("reg");
            }

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $produit, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                8/*limit per page*/
            );


            return $this->render('@Admin/Shop/reg.html.twig', array("reg" => $pagination, 'form' => $form->createView())
            );

        }
    }


    public function rechercheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->get('filter');
        $produit = $em->getRepository('ShopBundle:Produit')->findBy(array('nom' => $key));
        return $this->render('@Admin/Shop/produit.html.twig', array("produit" => $produit));

    }

    public function DeletecAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $cat = $am->getRepository("ShopBundle:Category")->find($id);
        $am->remove($cat);
        $am->flush();
        return $this->redirectToRoute("cat");
    }

    public function DeleterAction($id)
    {
        $am = $this->getDoctrine()->getManager();
        $cat = $am->getRepository("ShopBundle:Region")->find($id);
        $am->remove($cat);
        $am->flush();
        return $this->redirectToRoute("reg");
    }

    public function rechercheCategoryAction(Request $request)
    {
        $nom = $request->get("nom");
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository("ShopBundle:Category")->CategoryRecherche($nom);
        return new Response($this->renderView('@Admin/Shop/Recherche.html.twig', array('cat' => $cat)));
    }

    public function rechercheRegionAction(Request $request)
    {
        $nom = $request->get("nom");
        $em = $this->getDoctrine()->getManager();
        $reg = $em->getRepository("ShopBundle:Region")->RegionRecherche($nom);
        return new Response($this->renderView('@Admin/Shop/RechercheReg.html.twig', array('reg' => $reg)));
    }

}
