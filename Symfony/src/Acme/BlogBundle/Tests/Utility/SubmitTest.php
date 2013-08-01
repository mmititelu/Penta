<?php
namespace Acme\BlogBundle\Tests\Utility;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubmitTest extends WebTestCase
{
     public function testShow()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', 'login');
        //print_r($crawler->html());
               
        $form = $crawler->selectButton('submit')->form();
        $crawler = $client->submit($form, array('username' => 'admin', 'password' => 'admin'));
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Blog homepage")')->count()
        );
      
        
    }
}
?>