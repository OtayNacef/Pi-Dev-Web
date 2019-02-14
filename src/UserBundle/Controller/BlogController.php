<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\BlogLike;
use UserBundle\Entity\Comment;
use UserBundle\Entity\PubComment;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Blog controller.
 *
 */
class BlogController extends Controller
{
    /**
     * Lists all blog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blogs = $em->getRepository('UserBundle:Blog')->findAll();
        $query = $em->createQuery('SELECT V From UserBundle:Blog V order by V.likesnumber desc ')->setMaxResults(3);
        $blogmax = $query->getResult();
        return $this->render('blog/index.html.twig', array(
            'blogs' => $blogs,
            'blogsmax' => $blogmax,
        ));
    }

    public function comptealbumAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blogs = $em->getRepository('UserBundle:Blog')->findAll();

        return $this->render('@User/compte.html.twig', array(
            'blogs' => $blogs,
        ));
    }

    /**
     * Creates a new blog entity.
     *
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm('UserBundle\Form\BlogType', $blog);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blog->setAuthor($user);
            $blog->setRepliesnumber(0);

            $blog->setDateCreation(new \DateTime());

            $em->persist($blog);

            $em->flush();

            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }

        return $this->render('blog/new.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a blog entity.
     *
     */
    public function showAction(Request $request, Blog $blog)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $em->getRepository('UserBundle:Blog')->find($blog);
        $arr = array();


        $deleteForm = $this->createDeleteForm($blog);
        if ($request->isMethod('post')) {
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setBlog($post);
            $comment->setContent($request->get('comment-content'));
            $comment->setPublishdate(new \DateTime('now'));
            $post->setRepliesnumber($post->getRepliesnumber() + 1);
            $em->persist($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }
        $comments = $em->getRepository('UserBundle:Comment')->findByBlog($post);
        $numberofcomments = count($comments);
        return $this->render('blog/show.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
            'numberofcomments' => $numberofcomments,
            'comments' => $comments,
            'arr' => $arr
        ));
    }

    /**
     * Displays a form to edit an existing blog entity.
     *
     */
    public function editAction(Request $request, Blog $blog)
    {
        $deleteForm = $this->createDeleteForm($blog);
        $editForm = $this->createForm('UserBundle\Form\BlogType', $blog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_edit', array('id' => $blog->getId()));
        }

        return $this->render('blog/edit.html.twig', array(
            'blog' => $blog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a blog entity.
     *
     */
    public function deleteAction(Request $request, Blog $blog)
    {
        $form = $this->createDeleteForm($blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * Creates a form to delete a blog entity.
     *
     * @param Blog $blog The blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    public function monBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = new Blog();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $editForm = $this->createForm('UserBundle\Form\BlogType', $blog);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $blog->setAuthor($user);

            return $this->redirectToRoute('user_monblog');
        }
        $form = $this->createForm('UserBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blog->setAuthor($user);
            $blog->setDateCreation(new \DateTime());

            $em->persist($blog);

            $em->flush();
            return $this->redirectToRoute('user_monblog');
        }
        //-------------------supprimer photo
        if ($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p = $em->getRepository(blog::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("user_monblog");
            }
            return $this->redirectToRoute('user_monblog');
        }


        //----------------------------------
        $post = $em->getRepository(Blog::class)->findBy(array('author' => $user->getId()), array('dateCreation' => 'DESC'));
        return $this->render('@User/blog.html.twig', array(
            'curr_user' => $user, 'form' => $form->createView(), 'edit' => $editForm, 'post' => $post
        ));
    }

    public function likeBlogAction($id)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('UserBundle:Blog')->find($id);
        $love = new BlogLike();
        $love->setUser($user);
        $post->setLikesnumber($post->getLikesnumber() + 1);
        $love->setBlog($post);
        $em->persist($love);
        $em->flush();
        return $this->redirectToRoute('blog_show', array('id' => $post->getId()));
    }

    public function RechercheBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('UserBundle:Blog')->AjaxRecherche($requestString);
        if (!$entities) {
            $result['entities']['error'] = "there is no Blog ";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = [
                $entity->getTitle(),
                $entity->getContent(),
                $entity->getCategorie(),
                $entity->getImage(),
                $entity->getAuthor()->getNom(),
                $entity->getDateCreation()->format("Y-m-d"),
                $entity->getLikesnumber(),
                $entity->getRepliesnumber()

            ];

        }
        return $realEntities;
    }

    public function searchAction()
    {
        $request = $this->getRequest();
        $data = $request->request->get('search');


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p FROM UserBundle:Blog p
    WHERE p.name LIKE :data')
            ->setParameter('data', $data);


        $res = $query->getResult();

        return $this->render('FooTransBundle:Default:search.html.twig', array(
            'res' => $res));
}
