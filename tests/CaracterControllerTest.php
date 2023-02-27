<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaracterControllerTest extends WebTestCase
{
    public function testDisplay(): void
    {
        $client = static::createClient();
        $client->request('GET', '/caracter/470599538e39b61665ea758a9f591580bd22735c');
        $this->assertJsonResponse($client->getResponse());
    }

    # Asserts that a Response is in json
   public function assertJsonResponse($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

}
