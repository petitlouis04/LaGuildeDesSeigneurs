<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Caracter;

class AppFixtures extends Fixture
{
    # Sets the Character with its data
    public function setCharacter($kind, $characterName, $characterData): Caracter
    {
        $character = new Caracter();
        $character
            ->setKind(substr_replace($kind, '', -1))
            ->setName($characterName)
            ->setSurname($characterData['surname'])
            ->setCaste($characterData['caste'])
            ->setKnowledge($characterData['knowledge'])
            ->setIntelligence($characterData['intelligence'])
            ->setLife($characterData['life'])
            ->setImage(strtolower('/images/cartes/' . $kind . '/' . $characterName . '.jpg'))
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreated(new \DateTime())
        ;
        return $character;
    }

    public function load(ObjectManager $manager): void
    {
        # Creates All the Characters from json
        $characters = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/characters.json'), 2);
        foreach ($characters as $kind => $charactersData) {
            foreach ($charactersData as $characterName => $characterData) {
                $character = $this->setCharacter($kind, $characterName, $characterData);

                $manager->persist($character);
            }
        }

        $totalCharacters = 20;
                # Creates random Characters
                for ($i = 0; $i < $totalCharacters; $i++) {
                    $character = new Caracter();
                    $character
                        ->setKind(rand(0, 1) ? 'Dame' : 'Seigneur')
                        ->setName('Anardil' . $i)
                        ->setSurname('Amie du Soleil')
                        ->setCaste('Magicien')
                        ->setKnowledge('Sciences')
                        ->setIntelligence(mt_rand(100, 200))
                        ->setLife(mt_rand(10, 20))
                        ->setIdentifier(hash('sha1', uniqid()))
                        ->setImage('/images/cartes/dames/anardil.jpg')
                        ->setCreated(new \DateTime())
                    ;
                    $manager->persist($character);
                }

        $manager->flush();
    }

   
}
