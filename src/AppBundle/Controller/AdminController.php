<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @route ("/", name= "admin_home")
     * @return Response
     */

    public function indexAction(){
        return $this->render("admin/index.html.twig");
    }

    /**
     * @route("/login", name="admin_login")
     * @return Response
     */
    public function admin_loginAction(){

        $securityUtils = $this->get("security.authentication_utils");
        $lastUserName = $securityUtils->getLastUsername();
        $error = $securityUtils->getLastAuthenticationError();


        return $this->render("default/generic-login.html.twig", [
            "action" => $this->generateUrl("admin_login_check"),
            "title" =>"Login des administrateurs",
            "userName" => $lastUserName,
            "error" =>$error

        ]);
    }

    /**
     * @Route("/themes", name="admin_themes")
     * @return Response
     */
    public function themeAction(Request $request){
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $themeList = $repository->findAll();

        //Generation de formulaire

        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);

        //hydratation de l'entité (formulaire)

        $form->handleRequest( $request);

        //traité le formulaire

        if ($form->isSubmitted() and $form->isValid()){

            // persistence de l'entité
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            // Redirection pour eviter de poster deux fois les donées...

            return $this->redirectToRoute("admin_themes");
        }

        return $this->render("admin/theme.html.twig", [
            "themeList" => $themeList,
            "themeForm" => $form ->createView()]);

    }


    /**
     * @route("/secure" ,name= "admin_olny_god")
     * @return Reponse
     */

    public function onlyGodAction(){
        return $this->render("admin/god.html.twig");
    }
}