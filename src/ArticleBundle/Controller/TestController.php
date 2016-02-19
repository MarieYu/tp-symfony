<?php
namespace ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestController
 * @package ArticleBundle\Controller
 */
class TestController extends Controller{

    public function helloAction(){

        $authorizationChecker = $this->get("security.authorization_checker");

        if($authorizationChecker->isGranted("ROLE_ADMIN")){
            return new Response("hello Admin!");
        }
        return new Response("hello !");


    }
}