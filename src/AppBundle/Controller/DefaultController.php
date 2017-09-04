<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");
        $postRepostory = $this->getDoctrine()
            ->getRepository("AppBundle:Post");


        $list = $repository->getAllThemes()->getArrayResult();
        $postListByYear = $postRepostory->getPostsGroupedByYear();

        //$themeList = $repository->getAllThemes()->getArrayResult();

        //return $this->render('default/index.html.twig',
          //  ["themeList" => $themeList]);

        return $this->render('default/index.html.twig',
            ["postList" => $postListByYear,
                "themeList" => $list]);
    }

    /**
     * @Route("/theme/{id}", name="theme_details", requirements={"id":"\d+"})
     * @param $id
     * @return Response
     */
    public function themeAction($id){

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $theme = $repository->find($id);

        $allThemes = $repository->getAllThemes()->getResult();

        if(! $theme){
            throw new NotFoundHttpException("Thème introuvable");
        }


        return $this->render('default/theme.html.twig', [
            "theme" => $theme,
            "postList" => $theme->getPosts(),
            "all" => $allThemes
        ]);
    }
}
