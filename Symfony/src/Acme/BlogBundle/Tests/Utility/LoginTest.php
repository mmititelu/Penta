<?php
namespace Acme\BlogBundle\Tests\Utility;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
     public function testLogin()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', 'login');
        $form = $crawler->selectButton('submit')->form();

        // set some values
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';
        
        // submit the form
        $crawler = $client->submit($form);
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Blog homepage")')->count()
        );
      
        
    }
}
?>