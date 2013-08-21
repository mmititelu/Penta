<?php

namespace Acme\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Acme\AccountBundle\Form\Type\RegistrationType;
use Acme\AccountBundle\Form\Type\UserType;
use Acme\AccountBundle\Form\Model\Registration;
use Acme\AccountBundle\Document\User;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AccountBundle:Default:index.html.twig', array('name' => $name));
    }
    public function registerAction()
    {
        $form = $this->createForm(new RegistrationType(), new Registration());

        return $this->render('AccountBundle:Default:register.html.twig', array('form' => $form->createView()));
    }
     public function listuAction()
    {
    $products = $this->get('doctrine_mongodb')
        ->getRepository('AccountBundle:User')
        ->findAll();

    if (!$products) {
        throw $this->createNotFoundException('No products found!');
    }

    return $this->render('AccountBundle:Default:list.html.twig', array('products' => $products));
    }
public function createAction()
    {
    
        $user=new User();
        $form = $this->createForm(new UserType(), $user);
        $request = $this->getRequest();
        if($request->getMethod()=='POST')
        {
            $form->bind($request);
           // var_dump($form->isValid());
            //var_dump($enquiry->getTitle());
            //exit();
            if($form->isValid())
            {
             
              $dm = $this->get('doctrine_mongodb')->getManager();
              $dm->persist($user);
              $dm->flush();
              //$form = $this->createForm(new RegistrationType(), new Registration());
              return $this->redirect($this->generateUrl('account_redirect'));
             // $form->bind($this->getRequest());

            }
        }
    return $this->render('AccountBundle:Default:new.html.twig', array('form' => $form->createView()));
    }
    
    public function loginAction()
    {
        
        $request = $this->getRequest();
        $session = $request->getSession();
         if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) 
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('AccountBundle:Default:check.html.twig',array(
        // last username entered by the user
        'email'=>$session->get(SecurityContext::LAST_USERNAME),'error'=>$error));
        
       
      //  return $this->render('AccountBundle:Default:check.html.twig', array('form' => $form->createView()));
    }  
    public function checkAction(Request $request)
    {   //$session = $this->collection->findOne(array('session_id' => $id));
        $user = new User;
        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);
       // var_dump(sha1($request->request->get('_password')));
        $user = $this->get('doctrine_mongodb')
                ->getRepository('AccountBundle:User')
                 ->findOneBy
                     (array('email'=>$request->request->get('_email'),'password' => sha1($request->request->get('_password'))));
            
       
         if (!$user) {
            throw $this->createNotFoundException('No user found for email ');
         }
        // else {throw $this->createNotFoundException('uraaaa ');
         //exit();}
        // var_dump($request->request->get('_email'));
        // var_dump($request->request->get('_password'));
       //exit(); 
        return new RedirectResponse($this->get("router")->generate("account_redirect"));
    }
   
        public function logoutAction()
    {
        $this->get("request")->getSession()->invalidate();
        $this->get("security.context")->setToken(null);
        return new RedirectResponse($this->get("router")->generate("account_redirect"));
    }
}