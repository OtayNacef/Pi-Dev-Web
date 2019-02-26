<?php
/**
 * Created by PhpStorm.
 * User: ZerOo
 * Date: 2/17/2019
 * Time: 12:10 PM
 */

namespace ShopBundle\Controller;


use ShopBundle\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PanierController extends Controller
{


    public function addToPanierAction($id, Request $request)
    {

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
            $em = $this->getDoctrine()->getManager();
            $produit = $this->getDoctrine()
                ->getRepository('ShopBundle:Produit')
                ->find($id);
            $c = 0;


            foreach ($panierlist as $p) {
                if ($p->getProduitP() == $produit) {


                    $p->setQuantite($p->getQuantite() + ($request->get('quantity')));
                    $p->setPrix($p->getPrix() + ($produit->getPrix() * ($request->get('quantity'))));
                    $em->persist($p);
                    $em->flush();
                    $c = 1;
                    return $this->redirectToRoute('shop_homepage');
                }

            }
            if ($c != 0) {
                return $this->redirectToRoute('shop_homepage');
            } else {
                if ($request->isMethod('POST')) {
                    $panier = new Panier();
                    $panier->setUser($user);
                    $panier->setProduitP($produit);
                    $panier->setDateP($produit->getDate());
                    $panier->setQuantite(($request->get('quantity')));
                    $panier->setPrix($produit->getPrix() * ($request->get('quantity')));
                    $em->persist($panier);
                    $em->flush();

//                    return new Response(json_encode(array('status'=>'success')));
                }
            }


        }

        return $this->redirectToRoute('shop_homepage');

    }


    public function checkoutAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
            $count = count($panierlist);
            $total = 0;
            foreach ($panierlist as $prix) {

                $p = $prix->getPrix();
                $total = $p + $prix->getPrix();
            }

            return $this->render('@Shop/Default/checkout.html.twig', array('nbrp' => $count, 'total' => $total, 'panier' => $panierlist));
        }
    }

    public function deleteAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $produit = $sn->getRepository('ShopBundle:Panier')->find($id);
        $sn->remove($produit);
        $sn->flush();

        return $this->redirectToRoute('shop_checkout');

    }




}