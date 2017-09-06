<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Author;
use AppBundle\Entity\Post;
use AppBundle\Form\AuthorType;
use AppBundle\Form\PostType;
use function PHPSTORM_META\type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");
        $postRepostory = $this->getDoctrine()
            ->getRepository("AppBundle:Post");


        $list = $repository->getAllThemes()->getArrayResult();
        $postListByYear = $postRepostory->getPostsGroupedByYear();

        // Gestion des nouveaux posts

        $user = $this->getUser();
        $roles = isset($user)?$user->getRoles():[];
        $formView= null;
        if (in_array("ROLE_AUTHOR", $roles)) {

            // création du formulaire
            $post = new Post();
            $post->setCreatedAt(new  \DateTime());
            $post->setAuthor($user);
            $form = $this->createForm(PostType::class, $post);

            // Hydratation du formulaire
            $form->handleRequest($request);

            //traitement du formulaire
            if ($form->isSubmitted() and $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();

                //Redirection
                return $this->redirectToRoute("/");

            }

            $formView = $form->createView();

        }
        //$themeList = $repository->getAllThemes()->getArrayResult();

        //return $this->render('default/index.html.twig',
          //  ["themeList" => $themeList]);

        return $this->render('default/index.html.twig',
            ["postList" => $postListByYear,
                "themeList" => $list,
                "postForm" =>$formView

            ]);
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

    /**
     * @route("/inscription", name = "author_registration")
     * @param Request $request
     * @return Response
     */
    public function registrationAction(Request $request){
        $author = new Author();
        $form = $this->createForm(
            AuthorType::class,
            $author
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            //Encodage du mot de passe
            $encoderFactory = $this->get("security.encoder_factory");
            $encoder = $encoderFactory->getEncoder($author);
            $author->setPassword($encoder->encodePassword($author->getPlainPassword(), null));
            $author->setPlainPassword(null);

            //Enregitrement dans la bese de donnée

            $em->persist($author);
            $em->flush();
        }

        return $this ->render("default/author-registration.html.twig",
            [
                "registrationForm" => $form ->createView()
            ]);
    }


    /**
     * @route("/author-login", name="author_login")
     * @return Response
     */
    public function authorLoginAction(){
        $securityUtils = $this->get("security.authentication_utils");
        return $this->render("default/generic-login.html.twig",
            [
                "title" =>"Identification des auteurs",
                "action" => $this->generateUrl("author_login_check"),
                "userName" => $securityUtils->getLastUsername(),
                "error" =>$securityUtils->getLastAuthenticationError()
            ]);
    }
}
