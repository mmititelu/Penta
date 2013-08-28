<?php
namespace Acme\JobeetBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 
class CategoryControllerTest extends WebTestCase
{
  public function testShow()
  {
    $client = static::createClient();
 
    $crawler = $client->request('GET', '/category/programming/1');
    $this->assertEquals('Acme\JobeetBundle\Controller\CategoryController::showAction', $client->getRequest()->attributes->get('_controller'));
    $this->assertTrue(200 === $client->getResponse()->getStatusCode());
  }
}
?>
