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


class PanierController extends Controller
{


    public function addToPanierAction($id)
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


                    $p->setQuantite($p->getQuantite() + 1);
                    $p->setPrix(($p->getPrix() + $produit->getPrix()));
                    $em->persist($p);
                    $em->flush();
                    $c = 1;
                    return $this->redirectToRoute('shop_homepage');
                }

            }
            if ($c != 0) {
                return $this->redirectToRoute('shop_homepage');
            } else {
                $panier = new Panier();
                $panier->setUser($user);
                $panier->setProduitP($produit);
                $panier->setDateP($produit->getDate());
                $panier->setQuantite(1);
                $panier->setPrix($produit->getPrix());
                $em->persist($panier);
                $em->flush();
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

    public function detailsAction(Request $request, $id)
    {

        $panier = $this->getDoctrine()
            ->getRepository('ShopBundle:Panier')
            ->find($id);

        $now = new\DateTime('now');


        $panier->setQuantite($produits->getNom());
        $produits->setPrix($produits->getPrix());
        $produits->setDescription($produits->getDescription());
        $produits->setQuantity($produits->getQuantity());
        $produits->setImageId($produits->getImageId());
        $produits->setDate($now);

        $form = $this->createFormBuilder($produits)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('save', SubmitType::class, array('label'=>'create todo', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nom = $form['nom']->getData();
//            $category = $form['categorie']->getData();
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $photo = $form['imageId']->getData();
            $prix = $form['prix']->getData();
            $now = new\DateTime('now');

            $sn = $this->getDoctrine()->getManager();
            $produits = $sn
                ->getRepository('ShopBundle:Produit')
                ->find($id);

            $produits->setNom($nom);
//            $produit->setCategorie($category);
            $produits->setDescription($description);
            $produits->setQuantity($stock);
            $produits->setPrix($prix);
            $produits->setImageId($photo);
            $produits->setDisponible(1);
            $produits->setDate($now);

            $sn->flush();
            $this->addFlash(
                'notice',
                'todo updated'
            );

            return $this->redirectToRoute('shop_homepage');
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $p = $prix->getPrix();
            $total = $p + $prix->getPrix();
        }
        return $this->render('ShopBundle:Default:details.html.twig', array('nbrp' => $count, 'panier' => $panierlist,
            'total' => $total,
            'produit' => $produits,
            'form' => $form->createView()

        ));

    }


}