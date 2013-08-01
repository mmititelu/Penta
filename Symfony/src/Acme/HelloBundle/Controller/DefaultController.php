<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Response;
class DefaultController extends Controller
{
    public function indexAction($name)
    {
		//return new Response('<html><body>Hello <b>'.$name.'</b>!</body></html>');
       return $this->render('AcmeHelloBundle:Default:index.html.twig', array('name' => $name));
	 // throw $this->createNotFoundException('The prodict');
    }
}
