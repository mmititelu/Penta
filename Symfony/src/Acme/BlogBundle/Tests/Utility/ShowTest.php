<?php
namespace Acme\BlogBundle\Tests\Utility;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', 'list/1');
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nokia")')->count()
        );
    }
}
?>