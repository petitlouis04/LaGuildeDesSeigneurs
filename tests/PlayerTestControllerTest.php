<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class PlayerTestControllerTest extends WebTestCase
{
    private $client;

    private $content; // Contenu de la réponse
    private static $identifier; // Identifier du Character

    public function setUp() : void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('contact@example.com');
        $this->client->loginUser($testUser);
    }

    // Ce test doit être en premier car ils sont faits dans l'ordre
    // et il faut qu'au moins un Character soit défini pour le display
    # Tests creates
    public function testCreate()
    {
        $this->client->request('POST', '/player/create');
        $this->assertResponseCode(201);
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    # Tests display
    public function testDisplay()
    {
        $this->client->request('GET', '/player/display/' . self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    # Tests modify
    public function testModify()
    {
        $this->client->request('PUT', '/player/modify/' . self::$identifier);
        $this->assertResponseCode(204);
    }

    // Ce test doit être en dernier car ils sont faits dans l'ordre
    // et il vient supprimer le Character qui a été créé plus haut
    # Tests delete
    public function testDelete()
    {
        $this->client->request('DELETE', '/player/delete/' . self::$identifier);
        $this->assertResponseCode(204);
    }

    # Asserts that a Response is in json
    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    # Tests inexisting identifier
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/player/display/fcb0c0a27279515b186f0ba54d3508726622e07aerror');
        $this->assertResponseCode(404);
    }

    # Asserts that Response code is equal to $code
    public function assertResponseCode(int $code)
    {
        $response = $this->client->getResponse();
        $this->assertEquals($code, $response->getStatusCode());
    }

    # Asserts that 'identifier' is present in the Response
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }
    # Defines identifier
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
}
}
