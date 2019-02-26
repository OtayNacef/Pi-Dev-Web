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

    public function deleteAction($id)
    {

            $sn = $this->getDoctrine()->getManager();
        $produit = $sn->getRepository('ShopBundle:Reviews')->find($id);
        $sn->remove($produit);
            $sn->flush();

            return $this->redirectToRoute('shop_homepage');
        }

}