<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Produit;
use ShopBundle\Entity\Reviews;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class DefaultController extends Controller
{


    public function indexAction(Request $request)
    {

//        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

//        }
        $produit = new Produit();


        $form = $this->createFormBuilder($produit)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('save', SubmitType::class, array('label'=>'create todo', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $produit->getImageId();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored

            $file->move(
                $this->getParameter('images_shop'),
                $fileName
            );


            $produit->setImageId($fileName);

            $nom = $form['nom']->getData();
//            $category = $form['categorie']->getData();
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $prix = $form['prix']->getData();
            $now = new\DateTime('now');

            $produit->setNom($nom);
//            $produit->setCategorie($category);
            $produit->setDescription($description);
            $produit->setQuantity($stock);
            $produit->setPrix($prix);
            $produit->setUtilisateur($user);
            $produit->setDate($now);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($produit);
            $sn->flush();

            $this->addFlash(
                'notice',
                'todo added'
            );

//            return $this->redirectToRoute('Adminindex');
        }
        $products = $this->getDoctrine()->getRepository('ShopBundle:Produit')->findAll();
        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $total = $total + $prix->getPrix();
        }


        return $this->render('Shop.html.twig', array(

            'form' => $form->createView(), 'products' => $products, 'nbrp' => $count, 'panier' => $panierlist, 'total' => $total

        ));
    }

    public function deleteAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $produit = $sn->getRepository('ShopBundle:Produit')->find($id);
        $sn->remove($produit);
        $sn->flush();

        return $this->redirectToRoute('shop_homepage');

    }

    public function detailsAction(Request $request, $id)
    {

        $produits = $this->getDoctrine()
            ->getRepository('ShopBundle:Produit')
            ->find($id);


        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
        }

        $produits->setNom($produits->getNom());
        $produits->setDescription($produits->getDescription());
        $produits->setQuantity($produits->getQuantity());
        $produits->setPrix($produits->getPrix());

        $form2 = $this->createFormBuilder($produits)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('data_class' => null, 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('save', SubmitType::class, array('label'=>'create todo', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
            ->getForm();

        if ($form2->handleRequest($request)->isSubmitted()) {

            /** @var UploadedFile $file */
            $file = $produits->getImageId();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored

            $file->move(
                $this->getParameter('images_shop'),
                $fileName
            );


            $produits->setImageId($fileName);

            $nom = $form2['nom']->getData();
//            $category = $form['categorie']->getData();
            $description = $form2['description']->getData();
            $stock = $form2['quantity']->getData();
            $prix = $form2['prix']->getData();

            $produits->setNom($nom);
//            $produit->setCategorie($category);
            $produits->setDescription($description);
            $produits->setQuantity($stock);
            $produits->setPrix($prix);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($produits);
            $sn->flush();

            $this->addFlash(
                'notice',
                'todo added'
            );

            return $this->redirectToRoute('shop_homepage');
        }

        if ($request->isMethod('POST')) {

            $review = new Reviews();
            $review->setTitle(($request->get('title')));
            $review->setDescription(($request->get('description')));
            $review->setStars(($request->get('stars')));
            $review->setProduitP($produits);
            $review->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute("shop_homepage");

        }


        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $reviews = $this->getDoctrine()->getRepository('ShopBundle:Reviews')->findByProduitP($produits);
        $count = count($panierlist);
        $nbrrev = count($reviews);
        $total = 0;
        foreach ($panierlist as $prix) {

            $p = $prix->getPrix();
            $total = $p + $prix->getPrix();
        }
        $totlanbrR = 0;
        foreach ($reviews as $rating) {

            $totlanbrR = $totlanbrR + $rating->getStars();
        }
        if ($nbrrev == 0) {
            $res = 0;
        } else
            $res = $totlanbrR / $nbrrev;
        $produits->setStars($res);
        $em = $this->getDoctrine()->getManager();
        $em->persist($produits);
        $em->flush();
        return $this->render('ShopBundle:Default:details.html.twig', array('nbrp' => $count, 'panier' => $panierlist,
            'total' => $total,
            'produit' => $produits,
            'reviews' => $reviews,
            'rev' => $nbrrev,
            'rating' => $res,
            'edit' => $form2->createView()


        ));

    }


}
