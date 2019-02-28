<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\BonPlansBundle;
use BonPlansBundle\Entity\BonPlan;
use BonPlansBundle\Entity\Categorie;
use BonPlansBundle\Form\BonPlanType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

class BonPlanController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this ->render("@BonPlans\BonPlan\listbonplan.html.twig");
    }
    public function AfficherAction(Request $request)
    {
        $em= $this ->getDoctrine()->getManager();
        $bonplan =$em->getRepository("BonPlansBundle:BonPlan")->findAll();
        $rating=$em->getRepository("BonPlansBundle:RatingBonPlan")->findAll();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->findAll();
        $query = $em->createQuery('SELECT B From BonPlansBundle:BonPlan B order by B.note desc ')->setMaxResults(3);
        $bonplanmax = $query->getResult();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        //pagination
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $bonplan, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        $newbonplan = new BonPlan();
        $newbonplan->setUser($user);
        $newbonplan->setDatePublication(new \DateTime());

        $form = $this->createForm(BonPlanType::class, $newbonplan);

        $form -> handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            /** @var UploadedFile $file
             */
            $file = $newbonplan->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                ,$fileName);

            $newbonplan->setImage($fileName);
            $sn = $this->getDoctrine()->getManager();
            $sn->persist($newbonplan);
            $sn->flush();
            return $this->redirectToRoute('bon_plans_afficher_bon_plan');

        }

        // My pie chart
        $totalBonsPlans=$em->getRepository(BonPlan::class)->NombreDesBonsPlans();

        $BonsRestaurant=$em->getRepository(BonPlan::class)->NombreDesBonsPlansRestaurant();
        $nbr=(count($BonsRestaurant)*100/$totalBonsPlans);

        $BonsHotel=$em->getRepository(BonPlan::class)->NombreDesBonsPlansHotel();
        $nbr2=(count($BonsHotel)*100/$totalBonsPlans);

        $BonsCafe=$em->getRepository(BonPlan::class)->NombreDesBonsPlansCafe();
        $nbr3=(count($BonsCafe)*100/$totalBonsPlans);

        $BonsBus=$em->getRepository(BonPlan::class)->NombreDesBonsPlansBus();
        $nbr4=(count($BonsBus)*100/$totalBonsPlans);

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Nom du Bon Plan', 'Catégories'],

                ['Restaurant',      $nbr],
                ['Hotel',  $nbr2],
                ['Cafe', $nbr3],
                ['Bus',    $nbr4]


            ]
        );
        $pieChart->getOptions()->setTitle('Pourcentage des bons plans selon leurs catégories');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(300);
        $pieChart->getOptions()->setBackgroundColor('transparent');
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);



        return $this ->render("@BonPlans\BonPlan\listbonplan.html.twig",array
        ('bonsplans'=>$pagination,'categorie'=>$categorie,
            'bonsplansmax'=>$bonplanmax,'rating'=>$rating,
            'form'=>$form->createView(
                ),
            'piechart' => $pieChart)
        );

    }

    public function StatAction()
    {
        $pieChart = new PieChart();
        $em= $this ->getDoctrine()->getManager();
        $bonplan =$em->getRepository("BonPlansBundle:BonPlan")->findAll();

        $pieChart->getData()->setArrayToDataTable(
            [['Nom du Bon Plan', 'Note'],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('Statistique sur les bons plans par note');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('AppBundle::index.html.twig', array('piechart' => $pieChart));
    }




    public function filterBonPlanAction(Request $request,$idCategorie)
    {


        if ($request->isMethod('POST')){
            $em=$this->getDoctrine()->getManager();
            $categorie=$request->get('categorie');
            $bonplans=$em->getRepository("BonPlansBundle:BonPlan")->findByCategorie($categorie);
            return $this->render("@BonPlans/BonPlan/listbonplan.html.twig",array("bonsplans"=>$bonplans));

        }
        return $this->render("@BonPlans/BonPlan/listbonplan.html.twig");

    }
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository('BonPlansBundle:BonPlan')->findEntitiesByString($requestString);
        if(!$entities) {
            $result['entities']['error'] = "there is no user with this username";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities){
        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = [$entity->getName(), $entity->getAdresse(),
                $entity->getDescription(), $entity->getImage(),$entity->getNote(),$entity->getId(),$entity->getUser()->getId(),
                $entity->getPrix(),$entity->getdatePublication(),$entity->getUser()->getNom()];
        }
        return $realEntities;
    }

    public function CreateAction(Request $request)
    {

        $em= $this ->getDoctrine()->getManager();
        $categorie=$em->getRepository("BonPlansBundle:Categorie")->findAll();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $bonplan = new BonPlan();
        $bonplan->setUser($user);
        $bonplan->setDatePublication(new \DateTime());

        $form = $this->createForm(BonPlanType::class, $bonplan);

        $form -> handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            /** @var UploadedFile $file
             */
            $file = $bonplan->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                ,$fileName);

            $bonplan->setImage($fileName);
            $sn = $this->getDoctrine()->getManager();
            $sn->persist($bonplan);
            $sn->flush();
            return $this->redirectToRoute('bon_plans_afficher_bon_plan');

        }
        return $this->render('@BonPlans/BonPlan/ajout.html.twig'
            , array(
                'categorie'=>$categorie, 'form'=>$form->createView()));
    }
    public function ModifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $bonplan=$em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $form= $this->createForm(BonPlanType::class,$bonplan);
        $bonplan->setImage(new File($this->getParameter('BonPlan') . '/' . $bonplan->getImage()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file
            /** @var UploadedFile $file
             */
            $file = $bonplan->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                ,$fileName);

            $bonplan->setImage($fileName);
            $em->flush();

            return $this->redirectToRoute('bon_plans_afficher_bon_plan');
        }

        return $this->render('@BonPlans\BonPlan\ajout.html.twig',['f' => $form->createView()]);
    }

    public function SupprimerAction($name){
        $em =$this->getDoctrine()->getManager();
        $restaurant=$em->getRepository("BonPlansBundle:BonPlan")->find($name);
        $em->remove($restaurant);
        $em->flush();
        return  $this->redirectToRoute("bon_plans_afficher_restaurant");
    }




    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    function findByCategorieAction(Request $request,$type){
        $em=$this->getDoctrine()->getManager();
        if ($request ->isMethod("POST")){

            $bonplans=$em->getRepository("BonPlansBundle:BonPlan")->findByCategorie($type);
            return $this->redirectToRoute('bon_plans_afficher_bon_plan',array('bonplans'=>$bonplans));


        }
        return $this->render("@BonPlans\BonPlan\listbonplan.html.twig");
    }
}
