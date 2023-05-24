<?php

// tests/Controller/TeamControllerTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/teams');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Teams', $crawler->filter('h1')->text());
    }

    // You can add more test methods here to test other aspects of the TeamController.
}
