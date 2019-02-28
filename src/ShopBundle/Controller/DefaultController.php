<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Category;
use ShopBundle\Entity\Produit;
use ShopBundle\Entity\Region;
use ShopBundle\Entity\Reviews;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;


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
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                'choice_label' => 'category',


            ])
            ->add('region', EntityType::class, [
                // looks for choices from this entity
                'class' => Region::class,

                'choice_label' => 'region',


            ])
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
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $prix = $form['prix']->getData();
            $cat = $form['category']->getData();
            $reg = $form['region']->getData();
            $now = new\DateTime('now');

            $produit->setNom($nom);
            $produit->setDescription($description);
            $produit->setQuantity($stock);
            $produit->setPrix($prix);
            $produit->setUtilisateur($user);
            $produit->setCategory($cat);
            $produit->setRegion($reg);
            $produit->setStars(0);
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
        $cat = $this->getDoctrine()->getRepository('ShopBundle:Category')->findAll();
        $region = $this->getDoctrine()->getRepository('ShopBundle:Region')->findAll();

        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $total = $total + $prix->getPrix();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );


        return $this->render('Shop.html.twig', array(

            'cat' => $cat,
            'region' => $region,

            'form' => $form->createView(), 'products' => $pagination, 'nbrp' => $count, 'panier' => $panierlist, 'total' => $total

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

    public function gotodeAction($id)
    {

        return $this->redirect($this->generateUrl('shop_detailsproduct', array('id' => $id)));
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
        $produits->setCategory($produits->getCategory());
        $produits->setRegion($produits->getRegion());

        $form2 = $this->createFormBuilder($produits)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('data_class' => null, 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                'choice_label' => 'category',


            ])
            ->add('region', EntityType::class, [
                // looks for choices from this entity
                'class' => Region::class,

                'choice_label' => 'region',


            ])
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
            $cat = $form2['category']->getData();
            $reg = $form2['region']->getData();

            $produits->setNom($nom);
//            $produit->setCategorie($category);
            $produits->setDescription($description);
            $produits->setQuantity($stock);
            $produits->setPrix($prix);
            $produits->setRegion($reg);
            $produits->setCategory($cat);

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
        $last = $this->getDoctrine()->getRepository('ShopBundle:Produit')->findBy(array(), array('date' => 'DESC'), 3, 1);
        $reviews = $this->getDoctrine()->getRepository('ShopBundle:Reviews')->findByProduitP($produits);
        $count = count($panierlist);
        $nbrrev = count($reviews);
        $total = 0;
        foreach ($panierlist as $prix) {

            $p = $prix->getPrix();
            $total = $total + $p;
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
            'lastprod' => $last,
            'edit' => $form2->createView()


        ));

    }

    public function searchAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('ShopBundle:Produit')->findEntitiesByCat($requestString);
        if (!$entities) {
            $result['entities']['error'] = "il y a pas un souvenir avec ce nom";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {

        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] =
                [
                    $entity->getNom(),
                    $entity->getDescription(),
                    $entity->getQuantity(),
                    $entity->getCategory()->getCategory(),
                    $entity->getRegion()->getRegion(),
                    $entity->getImageId(),
                    $entity->getPrix()];
        }
        return $realEntities;
    }


    public function filterAction(Request $request)
    {
        //        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

//        }
        $produit = new Produit();


        $form = $this->createFormBuilder($produit)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                'choice_label' => 'category',


            ])
            ->add('region', EntityType::class, [
                // looks for choices from this entity
                'class' => Region::class,

                'choice_label' => 'region',


            ])
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
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $prix = $form['prix']->getData();
            $cat = $form['category']->getData();
            $reg = $form['region']->getData();
            $now = new\DateTime('now');

            $produit->setNom($nom);
            $produit->setDescription($description);
            $produit->setQuantity($stock);
            $produit->setPrix($prix);
            $produit->setUtilisateur($user);
            $produit->setCategory($cat);
            $produit->setRegion($reg);
            $produit->setStars(0);
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
        if (isset($_POST['categ'])) {
            $_SESSION['ala'] = $_POST['categ'];
        }
        $in = '(' . implode(',', $_SESSION['ala']) . ')';
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = 'SELECT a FROM ShopBundle:Produit a WHERE a.category IN ' . $in;
        $query = $em->createQuery($dql);
        $user = $this->getUser();
        $category = $em->getRepository('ShopBundle:Category')->findAll();
        $media = $em->getRepository('ShopBundle:Produit')->findAll();

        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $cat = $this->getDoctrine()->getRepository('ShopBundle:Category')->findAll();
        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $total = $total + $prix->getPrix();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        // parameters to template
        return $this->render('shop.html.twig', array('products' => $pagination,
            'user' => $user,
            'cat' => $category,
            'media' => $media, 'nbrp' => $count, 'panier' => $panierlist, 'total' => $total, 'form' => $form->createView()));
    }


    public function filter2Action(Request $request)
    {
        //        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

//        }
        $produit = new Produit();


        $form = $this->createFormBuilder($produit)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                'choice_label' => 'category',


            ])
            ->add('region', EntityType::class, [
                // looks for choices from this entity
                'class' => Region::class,

                'choice_label' => 'region',


            ])
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
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $prix = $form['prix']->getData();
            $cat = $form['category']->getData();
            $reg = $form['region']->getData();
            $now = new\DateTime('now');

            $produit->setNom($nom);
            $produit->setDescription($description);
            $produit->setQuantity($stock);
            $produit->setPrix($prix);
            $produit->setUtilisateur($user);
            $produit->setCategory($cat);
            $produit->setRegion($reg);
            $produit->setStars(0);
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
        if (isset($_POST['reg'])) {
            $_SESSION['ala'] = $_POST['reg'];
        }
        $in = '(' . implode(',', $_SESSION['ala']) . ')';
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = 'SELECT a FROM ShopBundle:Produit a WHERE a.region IN ' . $in;
        $query = $em->createQuery($dql);
        $user = $this->getUser();
        $reg = $em->getRepository('ShopBundle:Region')->findAll();
        $media = $em->getRepository('ShopBundle:Produit')->findAll();

        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $total = $total + $prix->getPrix();
        }
        $cat = $this->getDoctrine()->getRepository('ShopBundle:Category')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        // parameters to template
        return $this->render('shop.html.twig', array('products' => $pagination,
            'user' => $user,
            'cat' => $cat,
            'region' => $reg,
            'media' => $media, 'nbrp' => $count, 'panier' => $panierlist, 'total' => $total, 'form' => $form->createView()));
    }


}
