<?php

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
}