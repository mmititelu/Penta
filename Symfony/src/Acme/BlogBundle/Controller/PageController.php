<?php 
namespace Acme\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\BlogBundle\Entity\Enquiry;
use Acme\BlogBundle\Entity\Blog;
use Acme\BlogBundle\Form\EnquiryType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class PageController extends Controller
{
	public function indexAction()
	{
		return $this->render('BlogBundle:Page:index.html.twig');
	}
	
	public function showAction($id)
	{   $em = $this->getDoctrine()->getEntityManager();
		$blog = $em->getRepository('BlogBundle:Blog')->find($id);
        
		if(!$blog)
		{
			throw $this->createNotFoundException('Unable to find Blog post.');
		}
		return $this->render('BlogBundle:Page:show.html.twig', array('blog' => $blog));
	}
	public function listAction()
	{
		$posts = $this->get('doctrine')->getManager()
				->createQuery('SELECT p From BlogBundle:Blog p')
				->execute();
		return $this->render('BlogBundle:Page:list.html.twig',array('posts' => $posts));
	}
    
    public function newAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);
        
        $request = $this->getRequest();
        
        if($request->getMethod()=='POST')
        {
            $form->bind($request);
           // var_dump($form->isValid());
            //var_dump($enquiry->getTitle());
            //exit();
            if($form->isValid())
            {
                $blog = new Blog();
                $blog->setTitle($enquiry->getTitle());
                $blog->setContent($enquiry->getContent());
                $em = $this->getDoctrine()->getManager();
                $em->persist($blog);
                $em->flush();
              
                // creating the ACL
                $aclProvider = $this->get('security.acl.provider');
                $objectIdentity = ObjectIdentity::fromDomainObject($blog);
                $acl = $aclProvider->createAcl($objectIdentity);

                // retrieving the security identity of the currently logged-in user
                $securityContext = $this->get('security.context');
                $user = $securityContext->getToken()->getUser();
                $roles = $securityContext->getToken()->getRoles();
                $securityIdentity = UserSecurityIdentity::fromAccount($user);
    
                // grant owner access
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($acl);
                
                return $this->redirect($this->generateUrl('BlogBundle_new'));
            }
        }
        return $this->render('BlogBundle:Page:new.html.twig', array('form'=> $form->createView()));
    }
    
    public function deleteAction($id)
    {   
        if(false===$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
		$blog = $em->getRepository('BlogBundle:Blog')->find($id);
		if(!$blog)
		{
			throw $this->createNotFoundException('Unable to find Blog post.');
		}
        $em->remove($blog);
        $em->flush();
        return $this->redirect($this->generateUrl('BlogBundle_list'));
    }
    public function editAction($id)
	{   
        if(false===$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('BlogBundle:Blog')->find($id);
              
        $form = $this->createForm(new EnquiryType(), $blog);
         
        $request = $this->getRequest();
               
        if($request->getMethod()=='POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
               
                if(!$blog)
                {
                    throw $this->createNotFoundException('Unable to find Blog post.');
                }
               
                $blog->setTitle($blog->getTitle());
                $blog->setContent($blog->getContent());
                $em->flush();
                return $this->redirect($this->generateUrl('BlogBundle_list'));
            }
        }
        return $this->render('BlogBundle:Page:edit.html.twig', array('blog' => $blog,'form'=> $form->createView()));
        
    }
    
}