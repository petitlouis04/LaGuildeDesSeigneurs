<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class CharacterControllerTest extends WebTestCase
{
    private $client;

    private $content; // Contenu de la réponse
    private static $identifier; // Identifier du Character
    private static $userId;

    public function setUp() : void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('contact@example.com');
        self::$userId = $testUser->getId();
        $this->client->loginUser($testUser);
    }

    // Ce test doit être en premier car ils sont faits dans l'ordre
    // et il faut qu'au moins un Character soit défini pour le display
    # Tests creates
    public function testCreate()
    {
        $userId = self::$userId;
        $this->client->request(
                        'POST',
                        '/create',
                        array(),// Parameters
                        array(),// Files
                        array('CONTENT_TYPE' => 'application/json'),// Server
                         <<<JSON
                        {
                            "kind":"Dame",
                            "name":"Anardil",
                            "surname":"Amie du Soleil",
                            "caste":"Magicien",
                            "knowledge":"Sciences",
                            "intelligence":130,
                            "life":11,
                            "image":"/images/cartes/dames/anardil.jpg",
                            "user": "{$userId}"
                        }
                        JSON
                    );
        $this->assertResponseCode(201);
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    # Tests index
    public function testIndex()
    {
        $this->client->request('GET', '/caracter/index');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with page
        $this->client->request('GET', '/caracter/index?page=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with page and size
        $this->client->request('GET', '/caracter/index?page=1&size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with size
        $this->client->request('GET', '/caracter/index?size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    # Tests display
    public function testDisplay()
    {
        $this->client->request('GET', '/caracter/' . self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    # Tests modify
    public function testModify()
    {
        $this->client->request(
                        'PUT',
                        '/caracter/modify/' . self::$identifier,
                        array(),// Parameters
                        array(),// Files
                        array('CONTENT_TYPE' => 'application/json'),// Server
                        <<<JSON
                        {
                            "kind":"Seigneur",
                            "name":"Gorthol",
                            "surname":"Heaume",
                            "caste":"Diplomatie",
                            "knowledge":"Sciences",
                            "intelligence":110,
                            "life":13,
                            "image":"/images/cartes/seigneurs/gorthol.jpg"
                        }
                        JSON
                    );
        $this->assertResponseCode(204);
    }

    // Ce test doit être en dernier car ils sont faits dans l'ordre
    // et il vient supprimer le Character qui a été créé plus haut
    # Tests delete
    public function testDelete()
    {
        $this->client->request('DELETE', '/caracter/delete/' . self::$identifier);
        $this->assertResponseCode(204);
    }

    # Asserts that a Response is in json
    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }


    # Tests redirect index
    public function testRedirectIndex()
    {
        $this->client->request('GET', '/caracter');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    # Tests bad identifier
    public function testBadIdentifier()
    {
        $this->client->request('GET', '/caracter/badIdentifier');
        $this->assertResponseCode(404);
    }

    # Tests inexisting identifier
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/caracter/a6202cecd05310f3361bfe3d63c471addfdc1ea4error');
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

    # Tests images
    public function testImages()
    {
        //Tests without kind
        $this->client->request('GET', '/caracter/images');
        $this->assertJsonResponse();
        $this->client->request('GET', '/caracter/images/3');
        $this->assertJsonResponse();
    }

    public function testIntelligence(){
        $this->client->request('GET', '/caracter/intelligence/120');
        $this->assertJsonResponse();
    }
}