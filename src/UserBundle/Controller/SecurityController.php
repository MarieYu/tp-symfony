<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(){

        $helper = $this->get('security.authentication_utils');

        return $this->render('UserBundle:Security:login.html.twig',[
            "error" => $helper->getLastAuthenticationError()
        ]);

    }
}
