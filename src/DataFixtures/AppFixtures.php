<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Caracter;
use App\Entity\User;
use App\Entity\Player;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;   
    public function __construct(
                 UserPasswordHasherInterface $hasher
            ) {
                $this->hasher = $hasher;
            }

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

        # Creates Users
        $emails = [
            'contact@example.com',
            'info@example.com',
            'email@example.com',
        ];
        $users = [];
        foreach ($emails as $email) {
            $user = new User();
            $user
                ->setEmail($email)
                ->setPassword($this->hasher->hashPassword($user, 'StrongPassword*'))
                ->setCreated(new \DateTime())
                ->setModified(new \DateTime())
            ;
            // On définit seulement cet utilisateur comme admin
            if ('contact@example.com' === $email) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $manager->persist($user);
            $users[] = $user;
        
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
                ->setUser($users[array_rand($users)])
            ;
            $manager->persist($character);
        }
        # Creates random Players
        $totalPlayers=20;
        $players = [];
        for ($i = 0; $i < $totalPlayers; $i++) {
            $player = new Player();
            $player
            ->setFirstname('Laurent' . $i)
            ->setLastname('Marquet' . $i)
            ->setEmail('email' . $i . 'example.com')
            ->setMirian(rand(0, 100000))
            ->setIdentifier(hash('sha1', uniqid()))
            ;
            $manager->persist($player);
            // Used to link to Characters
            $players[] = $player;

            $manager->flush();
        }
    }
}
