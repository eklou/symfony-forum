<?php

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{

    /**
     * @param $slug
     * @Route("/post/{slug}",
     *          name="post_details",
     * )
     * @return Response
     */
    public function detailsAction($slug){

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        /** @var $post Post */


        $post = $repository->findOneBySlug($slug);

        if(! $post){
            throw new NotFoundHttpException("post introuvable");
        }

        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $post->getAnswers()
        ]);

    }

    /**
     * @route("/post-par-annee/{year}", name="post_by_year")
     * @param $year
     * @return Response
     */

    public function postByYearAction($year){

        $postrepository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        return $this->render("default/theme.html.twig",[
            "title" => "Liste des posts par année ({$year})",
            "postList" => $postrepository->getPostByYear($year)
        ]);
    }

    /**
     * @route("/post/modif/{id}", name="post_edit")
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function editAction(Request $request, Post $post){

        $user = $this->getUser();
        $role = isset($user)?$user->getRoles():[];
        $userId = isset($user)?$user->getId(): null;
        if ((!in_array("ROLE_AUTHOR", $role) || $userId != $post->getAuthor()->getId() )){
            throw new  AccessDeniedException("vous n'avez pas les droits pour modifier ce post");
        }

        //creation du formulaire
        $form = $this->createForm(PostType:: class, $post);

        //hydratation de l'entité
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em ->persist($post);
            $em->flush();

            //redirection
            return $this->redirectToRoute(
                "theme_details",
                ["id" =>$post->getTheme()->getId()]
            );
        }

        return $this->render("post/edit.html.twig", ["postForm" =>$form->createView()]);
    }
}