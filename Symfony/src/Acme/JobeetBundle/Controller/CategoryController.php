<?php
namespace Acme\JobeetBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\JobeetBundle\Entity\Category;
 
/**
* Category controller.
*
*/
class CategoryController extends Controller
{
    public function showAction($slug, $page)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('JobeetBundle:Category')->findOneBySlug($slug);
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        } 
        $total_jobs = $em->getRepository('JobeetBundle:Job')->countActiveJobs($category->getId());
        $jobs_per_page = 10;
        $last_page = ceil($total_jobs / $jobs_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;
 
        $category->setActiveJobs($em->getRepository('JobeetBundle:Job')->getActiveJobs($category->getId(), $jobs_per_page, ($page - 1) * $jobs_per_page));
 
        $format = $this->getRequest()->getRequestFormat();
 
        return $this->render('JobeetBundle:Category:show.'.$format.'.twig', array(
            'category' => $category,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total_jobs' => $total_jobs,
            'feedId' => sha1($this->get('router')->generate('JobBundle_category', array('slug' =>  $category->getSlug(), '_format' => 'atom'), true)),
            ));
    }
 
}
?>
