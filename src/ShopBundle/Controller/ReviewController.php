<?php
/**
 * Created by PhpStorm.
 * User: ZerOo
 * Date: 2/20/2019
 * Time: 1:21 PM
 */

namespace ShopBundle\Controller;

use ShopBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class ReviewController extends Controller
{

    public function addReviewAction()
    {


        $review = new Reviews();
        $form = $this->createFormBuilder($review)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('categorie', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('stars', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('save', SubmitType::class, array('label'=>'create todo', 'attr'=>array('class'=>'btn btn-primary', 'style'=>'margin-bottom:15px')))
            ->getForm();


        if ($form->handleRequest($request)->isSubmitted()) {
            $title = $form['title']->getData();
//            $category = $form['categorie']->getData();
            $description = $form['description']->getData();
            $stars = $form['stars']->getData();

            $now = new\DateTime('now');

            $review->setTitle($title);
//            $produit->setCategorie($category);
            $review->setDescription($description);
            $review->setUser($user);
            $review->setProduitP($produits);
            $review->setStars($stars);


            $sn = $this->getDoctrine()->getManager();
            $sn->persist($review);
            $sn->flush();

            $this->addFlash(
                'notice',
                'todo added'
            );

            return $this->redirectToRoute('shop_homepage');
        }

        return $this->render('ShopBundle:Default:details.html.twig', array(
            'form' => $form->createView(),


        ));
    }

}