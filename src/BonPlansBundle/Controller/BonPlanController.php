<?php

namespace BonPlansBundle\Controller;

use BonPlansBundle\BonPlansBundle;
use BonPlansBundle\Entity\BonPlan;
use BonPlansBundle\Entity\Categorie;
use BonPlansBundle\Form\BonPlanType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

class BonPlanController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render("@BonPlans\BonPlan\listbonplan.html.twig");
    }

    public function AfficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->findAll();
        $rating = $em->getRepository("BonPlansBundle:RatingBonPlan")->findAll();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->findAll();
        $query = $em->createQuery('SELECT B From BonPlansBundle:BonPlan B order by B.note desc ')->setMaxResults(3);
        $bonplanmax = $query->getResult();
        return $this->render("@BonPlans\BonPlan\listbonplan.html.twig", array
        ('bonsplans' => $bonplan, 'categorie' => $categorie, 'bonsplansmax' => $bonplanmax, 'rating' => $rating));

    }

    public function filterBonPlanAction(Request $request, $idCategorie)
    {


        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $categorie = $request->get('categorie');
            $bonplans = $em->getRepository("BonPlansBundle:BonPlan")->findByCategorie($categorie);
            return $this->render("@BonPlans/BonPlan/listbonplan.html.twig", array("bonsplans" => $bonplans));

        }
        return $this->render("@BonPlans/BonPlan/listbonplan.html.twig");

    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('BonPlansBundle:BonPlan')->findEntitiesByString($requestString);
        if (!$entities) {
            $result['entities']['error'] = "there is no user with this username";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = [$entity->getName(), $entity->getAdresse(),
                $entity->getDescription(), $entity->getImage(), $entity->getNote(), $entity->getId(),
                $entity->getId(), $entity->getdatePublication()];
        }
        return $realEntities;
    }

    public function CreateAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("BonPlansBundle:Categorie")->findAll();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $bonplan = new BonPlan();
        $bonplan->setUser($user);
        $bonplan->setDatePublication(new \DateTime());

        $form = $this->createForm(BonPlanType::class, $bonplan);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            /** @var UploadedFile $file
             */
            $file = $bonplan->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                , $fileName);

            $bonplan->setImage($fileName);
            $sn = $this->getDoctrine()->getManager();
            $sn->persist($bonplan);
            $sn->flush();
            return $this->redirectToRoute('bon_plans_afficher_bon_plan');

        }
        return $this->render('@BonPlans/BonPlan/ajout.html.twig'
            , array(
                'categorie' => $categorie, 'form' => $form->createView()));
    }

    public function ModifierAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $bonplan = $em->getRepository("BonPlansBundle:BonPlan")->find($id);
        $form = $this->createForm(BonPlanType::class, $bonplan);
        $bonplan->setImage(new File($this->getParameter('BonPlan') . '/' . $bonplan->getImage()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file
             */
            $file = $bonplan->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('bonPlan')
                , $fileName);

            $bonplan->setImage($fileName);
            $em->flush();

            return $this->redirectToRoute('bon_plans_afficher_bon_plan');
        }

        return $this->render('@BonPlans\BonPlan\ajout.html.twig', ['f' => $form->createView()]);
    }

    public function SupprimerAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $restaurant = $em->getRepository("BonPlansBundle:BonPlan")->find($name);
        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute("bon_plans_afficher_restaurant");
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

    function findByCategorieAction(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod("POST")) {

            $bonplans = $em->getRepository("BonPlansBundle:BonPlan")->findByCategorie($type);
            return $this->redirectToRoute('bon_plans_afficher_bon_plan', array('bonplans' => $bonplans));


        }
        return $this->render("@BonPlans\BonPlan\listbonplan.html.twig");
    }
}
