<?php

namespace Acme\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\JobeetBundle\Entity\Job;
use Acme\JobeetBundle\Form\JobType;


/**
 * Job controller.
 *
 */
class JobController extends Controller
{

    /**
     * Lists all Job entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('JobeetBundle:Category')->getWithJobs();
     
        foreach($categories as $category)
        {
            $category->setActiveJobs($em->getRepository('JobeetBundle:Job')->getActiveJobs($category->getId(),10));
            $category->setMoreJobs($em->getRepository('JobeetBundle:Job')->countActiveJobs($category->getId())-10 );
        }
        $format = $this->getRequest()->getRequestFormat();
 
       return $this->render('JobeetBundle:Job:index.'.$format.'.twig', array(
         'categories' => $categories,
//         'lastUpdated' => $em->getRepository('JobeetBundle:Job')->getLatestPost()->getCreatedAt()->format(DATE_ATOM),
//         'feedId' => sha1($this->get('router')->generate('JobBundle', array('_format'=> 'atom'), true)),
        ));
    }
    
    public function createAction(Request $request)
    {
        $entity  = new Job();
        $form = $this->createForm(new JobType(), $entity);
        $request = $this->getRequest();
        
        if($request->getMethod()=='POST')
        {
            $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('JobBundle_preview', array(
                'company' => $entity->getCompanySlug(),
                'location' => $entity->getLocationSlug(),
                'token' => $entity->getToken(),
                
                )));
        }
        }
        //var_dump($form);
        $errors = $form->getErrorsAsString();
        if (count($errors) > 0)
        echo 'List of Errors:' . '<br>';
        {
         //  foreach ($errors as $name => $error)
           {
            echo  $errors ;
           }
        }
        return $this->render('JobeetBundle:Job:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Job entity.
     *
     */
    public function newAction()
    {
        $entity = new Job();
        $entity->setType('full-time');
        $form   = $this->createForm(new JobType(), $entity);

        return $this->render('JobeetBundle:Job:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
   
    /**
     * Finds and displays a Job entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JobeetBundle:Job')->find($id);
      //  var_dump($entity);
        //exit();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }
        
        $session = $this->getRequest()->getSession();
 
         // fetch jobs already stored in the job history
         $jobs = $session->get('job_history', array());
 
            // store the job as an array so we can put it in the session and avoid entity serialize errors
          $job = array('id' => $entity->getId(), 'position' =>$entity->getPosition(), 'company' => $entity->getCompany(), 'companyslug' => $entity->getCompanySlug(), 'locationslug' => $entity->getLocationSlug(), 'positionslug' => $entity->getPositionSlug());
 
            if (!in_array($job, $jobs)) {
                // add the current job at the beginning of the array
                array_unshift($jobs, $job);
 
                // store the new job history back into the session
                $session->set('job_history', array_slice($jobs, 0, 3));
            }
        
        $deleteForm = $this->createDeleteForm($id);
//        $publishForm = $this->createPublishForm($entity->getToken());
//        $extendForm = $this->createExtendForm($entity->getToken());
        return $this->render('JobeetBundle:Job:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
//            'publish_form' => $publishForm->createView(),
//            'extend_form' => $extendForm->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Job entity.
     *
     */
    public function editAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }
//          if ($entity->getIsActivated()) {
//                 throw $this->createNotFoundException('Job is activated and cannot be edited.');
//            }
        $editForm = $this->createForm(new JobType(), $entity);
        $deleteForm = $this->createDeleteForm($token);

        return $this->render('JobeetBundle:Job:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Job entity.
     *
     */
    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createForm(new JobType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('JobBundle_preview', array(
                'company' => $entity->getCompanySlug(),
                'location' => $entity->getLocationSlug(),
                'token' => $entity->getToken(),
                               )));
        }

        return $this->render('JobeetBundle:Job:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Job entity.
     *
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('JobBundle'));
    }

    /**
     * Creates a form to delete a Job entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
            ->add('token', 'hidden')
            ->getForm()
        ;
    }
    public function previewAction($token)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);
           // var_dump($entity);
            //exit();
 
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }
        
        $deleteForm = $this->createDeleteForm($entity->getToken());
        $publishForm = $this->createPublishForm($entity->getToken());
        $extendForm = $this->createExtendForm($entity->getToken());
 
        return $this->render('JobeetBundle:Job:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $publishForm->createView(),
            'extend_form' => $extendForm->createView(),
            ));
    }
    
    public function publishAction($token)
    {
        $form = $this->createPublishForm($token);
        $request = $this->getRequest();
            
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);
 
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }
 
            $entity->publish();
            $em->persist($entity);
            $em->flush();
 
           // $this->get('session')->setFlash('notice', 'Your job is now online for 30 days.');
        }
 
        return $this->redirect($this->generateUrl('JobBundle_preview', array(
            'company' => $entity->getCompanySlug(),
            'location' => $entity->getLocationSlug(),
            'token' => $entity->getToken(),
            
            )));
    }
 
    private function createPublishForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
            ->add('token', 'hidden')
            ->getForm();
    }
    
    public function extendAction($token)
    {
        $form = $this->createExtendForm($token);
        $request = $this->getRequest();
 
        $form->bind($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('JobeetBundle:Job')->findOneByToken($token);
 
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }
 
            if (!$entity->extend()) {
                throw $this->createNotFoundException('Unable to find extend the Job.');
            }
 
            $em->persist($entity);
            $em->flush();
 
            $this->get('session')->setFlash('notice', sprintf('Your job validity has been extended until %s.', $entity->getExpiresAt()->format('m/d/Y')));
        }
 
        return $this->redirect($this->generateUrl('JobBundle_preview', array(
            'company' => $entity->getCompanySlug(),
            'location' => $entity->getLocationSlug(),
            'token' => $entity->getToken(),
            'position' => $entity->getPositionSlug()
            )));
    }
 
    private function createExtendForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
         ->add('token', 'hidden')
         ->getForm();
    }
    
        public function searchAction(Request $request)
    {   
        
        $em = $this->getDoctrine()->getManager();
        $query = $this->getRequest()->get('query');
 
        if(!$query) {
            return $this->redirect($this->generateUrl('JobBundle'));
        }
 
        $jobs = $em->getRepository('JobeetBundle:Job')->getForLuceneQuery($query);
 
        return $this->render('JobeetBundle:Job:search.html.twig', array('jobs' => $jobs));
    
    }
   
}
