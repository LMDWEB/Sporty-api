<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DiaryControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testApipageIsUp()
    {
        $client = static::createClient();

        $client->request('GET', '/api/games');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}