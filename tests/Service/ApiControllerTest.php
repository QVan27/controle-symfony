<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetAll()
    {
        $client = static::createClient();
        $client->request('GET', '/getall');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetId()
    {
        $client = static::createClient();
        $client->request('GET', '/get/31');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
