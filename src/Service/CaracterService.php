<?php
//src/Service/CharacterService.php
namespace App\Service;
use DateTime;
use App\Entity\Caracter;
use App\Service\CaracterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CaracterService implements CaracterServiceInterface
{
    public EntityManagerInterface $em;
    
    public function __construct(
         EntityManagerInterface $em
            ) {
                $this->em= $em;
            }

    public function create(): Caracter
    {
        $character = new Caracter();
        $character
            ->setKind('Dame')
            ->setName('Anardil')
            ->setSurname('Amie du Soleil')
            ->setCaste('Magicien')
            ->setKnowledge('Sciences')
            ->setIntelligence(130)
            ->setLife(11)
            ->setImage('/images/cartes/dames/anardil.jpg')
            ->setCreated(new \DateTime())
        ;
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}