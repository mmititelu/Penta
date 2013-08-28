<?php

namespace Acme\JobBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\JobBundle\Entity\Job;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JobBundle:Default:index.html.twig', array('name' => $name));
    }
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
         'SELECT j FROM JobBundle:Job j WHERE j.expiresAt > :date'
         )->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30));
         $query->setMaxResults(10);
        $jobs = $query->getResult();
      

        if(!$jobs)
        {
           throw $this->createNotFoundException('Unable to find Jobs.');
        }
        return $this->render('JobBundle:Default:list.html.twig', array('jobs' => $jobs));
    }
    public function createAction()
    {
        $job = new Job();
        $job->setPosition('Web developer');
        $job->setType('Full Time');
        $job->setCompany('Pentalog');
        $job->setLocation('Iasi');
        $job->setEmail('admin@admin.com');
 
        // get the entity manager
        $em = $this->get('doctrine.orm.entity_manager');

        // persist the object to database
        $em->persist($job);
        $em->flush();

        return $this->redirect($this->generateUrl('JobBundle_list'));
    }
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository('JobBundle:Job')->find($id);
     
        return $this->render('JobBundle:Default:show.html.twig', array('job' => $job));
    }

    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository('JobBundle:Job')->find($id);
     
        $job->setPosition('Web designer');

        $em->persist($job);
        $em->flush();

        return $this->redirect($this->generateUrl('JobBundle_list'));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository('JobBundle:Job')->find($id);

        $em->remove($job);
        $em->flush();

        return $this->redirect($this->generateUrl('JobBundle_list'));
    }
}
