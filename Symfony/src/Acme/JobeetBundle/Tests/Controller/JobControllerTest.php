<?php

namespace Acme\JobeetBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 
class JobControllerTest extends WebTestCase
{

 //   public function getMostRecentProgrammingJob()
//  {
//    $kernel = static::createKernel();
//    $kernel->boot();
//    $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
// 
//    $query = $em->createQuery('SELECT j from JobeetBundle:Job j LEFT JOIN j.category c WHERE c.slug = :slug AND j.expires_at > :date ORDER BY j.created_at DESC');
//    $query->setParameter('slug', 'programming');
//    $query->setParameter('date', date('Y-m-d H:i:s', time()));
//    $query->setMaxResults(1);
//    return $query->getSingleResult();
//  }
 
  //public function getExpiredJob()
//  {
//    $kernel = static::createKernel();
//    $kernel->boot();
//    $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
// 
//    $query = $em->createQuery('SELECT j from JobeetBundle:Job j WHERE j.expires_at < :date');     $query->setParameter('date', date('Y-m-d H:i:s', time()));
//    $query->setMaxResults(1);
//    return $query->getSingleResult();
//  }
 
  //public function testIndex()
//  {
//    // get the custom parameters from app config.yml
//    $kernel = static::createKernel();
//    $kernel->boot();
//    $max_jobs_on_homepage = 10;
//    $max_jobs_on_category = 20;
// 
//    $client = static::createClient();
// 
//    $crawler = $client->request('GET', '/');
//    $this->assertEquals('Acme\JobeetBundle\Controller\JobController::indexAction', $client->getRequest()->attributes->get('_controller'));
// 
//    // expired jobs are not listed
//    $this->assertTrue($crawler->filter('.jobs td.position:contains("Expired")')->count() == 0);
// 
//    // only $max_jobs_on_homepage jobs are listed for a category
//   // $this->assertTrue($crawler->filter('.category_programming tr')->count()assertTrue($crawler->filter('.category_design .more_jobs')->count() == 0);
//    $this->assertTrue($crawler->filter('.category_programming .more_jobs')->count() == 1);
// 
//    // jobs are sorted by date
//    $this->assertTrue($crawler->filter('.category_programming tr')->first()->filter(sprintf('a[href*="/%d/"]', $this->getMostRecentProgrammingJob()->getId()))->count() == 1);
// 
//    // each job on the homepage is clickable and give detailed information
//    $job = $this->getMostRecentProgrammingJob();
//    $link = $crawler->selectLink('Web Developer')->first()->link();
//    $crawler = $client->click($link);
//    $this->assertEquals('Acme\JobeetBundle\Controller\JobController::showAction', $client->getRequest()->attributes->get('_controller'));
//    $this->assertEquals($job->getCompanySlug(), $client->getRequest()->attributes->get('company'));
//    $this->assertEquals($job->getLocationSlug(), $client->getRequest()->attributes->get('location'));
//    $this->assertEquals($job->getPositionSlug(), $client->getRequest()->attributes->get('position'));
//    $this->assertEquals($job->getId(), $client->getRequest()->attributes->get('id'));
// 
//    // a non-existent job forwards the user to a 404
//    $crawler = $client->request('GET', '/job/foo-inc/milano-italy/0/painter');
//    $this->assertTrue(404 === $client->getResponse()->getStatusCode());
// 
//    // an expired job page forwards the user to a 404
//    $crawler = $client->request('GET', sprintf('/job/sensio-labs/paris-france/%d/web-developer', $this->getExpiredJob()->getId()));
//    $this->assertTrue(404 === $client->getResponse()->getStatusCode());
//  }
    public function testJobForm()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/new');
      //  var_dump($client->getResponse()->getContent());
      //  $this->assertEquals('Acme\JobeetBundle\Controller\JobController::newAction', $client->getRequest()->attributes->get('_controller'));
            
        $form = $crawler->selectButton('Preview your job')->form(array(
            'job[company]'      => 'Sensio Labs',
            'job[url]'          => 'http://www.sensio.com/',
            'job[logo]'         => __DIR__.'/../../../../../web/bundles/jobeet/images/sensio-labs.gif',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[description]'  => 'You will work with symfony to develop websites for our customers.',
            'job[how_to_apply]' => 'Send me an email',
            'job[email]'        => 'for.a.job@example.com',
            'job[is_public]'    => false,
        ));
 
        $crawler = $client->submit($form);
        
        $this->assertEquals('Acme\JobeetBundle\Controller\JobController::createAction', $client->getRequest()->attributes->get('_controller'));
    }
    
//    public function createJob($values = array())
//{
//  $client = static::createClient();
//  $crawler = $client->request('GET', '/new');
//  $form = $crawler->selectButton('Preview your job')->form(array_merge(array(
//    'job[company]'      => 'Sensio Labs',
//    'job[url]'          => 'http://www.sensio.com/',
//    'job[position]'     => 'Developer',
//    'job[location]'     => 'Atlanta, USA',
//    'job[description]'  => 'You will work with symfony to develop websites for our customers.',
//    'job[how_to_apply]' => 'Send me an email',
//    'job[email]'        => 'for.a.job@example.com',
//   'job[is_public]'    => false,
//  ), $values));
// 
//  $client->submit($form);
//  $client->followRedirect();
// 
//  return $client;
//}
//    public function testPublishJob()
//{
//  $client = $this->createJob(array('job[position]' => 'FOO1'));
//  $crawler = $client->getCrawler();
//  $form = $crawler->selectButton('Publish')->form();
//  $client->submit($form);
// 
//  $kernel = static::createKernel();
//  $kernel->boot();
//  $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
// 
//  $query = $em->createQuery('SELECT count(j.id) from JobeetBundle:Job j WHERE j.position = :position AND j.is_activated = 1');
//  $query->setParameter('position', 'FOO1');
//  $this->assertTrue(0 < $query->getSingleScalarResult());
//}
//    public function testDeleteJob()
//{
//  $client = $this->createJob(array('job[position]' => 'FOO2'));
//  $crawler = $client->getCrawler();
//  $form = $crawler->selectButton('Delete')->form();
//  $client->submit($form);
// 
//  $kernel = static::createKernel();
//  $kernel->boot();
//  $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
// 
//  $query = $em->createQuery('SELECT count(j.id) from JobeetBundle:Job j WHERE j.position = :position');
//  $query->setParameter('position', 'FOO2');
//  $this->assertTrue(0 == $query->getSingleScalarResult());
//}
}
?>
