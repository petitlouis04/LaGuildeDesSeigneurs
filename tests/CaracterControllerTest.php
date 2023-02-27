<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaracterControllerTest extends WebTestCase
{
    private $client;
    public function setUp() : void
    {
        $this->client = static::createClient();
    }

    public function testDisplay(): void
    {
        $this->client->request('GET', '/caracter/470599538e39b61665ea758a9f591580bd22735c');
        $this->assertJsonResponse($this->client->getResponse());
    }

    # Asserts that a Response is in json
   public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    # Tests index
    public function testIndex()
    {
        $this->client->request('GET', '/caracter/index');
        $this->assertJsonResponse($this->client->getResponse());
    }

    # Tests redirect index
    public function testRedirectIndex()
    {
        $this->client->request('GET', '/caracter');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    # Tests bad identifier
    /*public function testBadIdentifier()
    {
        $this->client->request('GET', '/caracter/display/badIdentifier');
        $this->assertError404();
    }
    # Asserts that Response returns 404
    public function assertError404($statusCode)
    {
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/caracter/470599538e39b61665ea758a9f591580bd22735c');
        $this->assertError404();
    }*/

}
