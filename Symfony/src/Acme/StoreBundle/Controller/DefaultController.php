<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Acme\StoreBundle\Document\Product;
use Acme\StoreBundle\Form\ProductType;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('StoreBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function createAction()
    {
    $product = new Product();
    $form = $this->createForm(new ProductType(), $product);
    $request = $this->getRequest();
               
        if($request->getMethod()=='POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $product->setName($product->getName());
                $product->setPrice($product->getPrice());

                 $dm = $this->get('doctrine_mongodb')->getManager();
                 $dm->persist($product);
                 $dm->flush();
                 return $this->redirect($this->generateUrl('store_list'));
            }
        }
      return $this->render('StoreBundle:Default:new.html.twig', array('product' => $product,'form'=> $form->createView()));
    }
    public function listAction()
    {
    $products = $this->get('doctrine_mongodb')
        ->getRepository('StoreBundle:Product')
        ->findAll();

    if (!$products) {
        throw $this->createNotFoundException('No products found!');
    }

    return $this->render('StoreBundle:Default:list.html.twig', array('products' => $products));
    }
    
    public function showAction($id)
    {
    $product = $this->get('doctrine_mongodb')
        ->getRepository('StoreBundle:Product')
        ->find($id);

    if (!$product) {
        throw $this->createNotFoundException('No product found for id '.$id);
    }

    return $this->render('StoreBundle:Default:show.html.twig', array('product' => $product));
    }
    public function updateAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $product = $dm->getRepository('StoreBundle:Product')->find($id);
        $form = $this->createForm(new ProductType(), $product);
         
        $request = $this->getRequest();
               
        if($request->getMethod()=='POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                 if (!$product) {
                      throw $this->createNotFoundException('No product found for id '.$id);
                 }   

            $product->setName($product->getName());
            $product->setPrice($product->getPrice());
            $dm->flush();
            return $this->redirect($this->generateUrl('store_list'));
            }
        }

     return $this->render('StoreBundle:Default:edit.html.twig', array('product' => $product,'form'=> $form->createView()));
    }
    
}
