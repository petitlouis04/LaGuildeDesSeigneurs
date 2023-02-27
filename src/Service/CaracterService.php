<?php
//src/Service/CharacterService.php
namespace App\Service;
use DateTime;
use App\Entity\Caracter;
use App\Service\CaracterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CaracterRepository;

class CaracterService implements CaracterServiceInterface
{
    public EntityManagerInterface $em;
    private CaracterRepository $caracterRepository;

    public function __construct(
         EntityManagerInterface $em,
         CaracterRepository $caracterRepository
            ) {
                $this->em= $em; 
                $this->caracterRepository = $caracterRepository;
            }

    /*public function findOneByIdentifier($identifier): Caracter
    {
        return $this->caracterRepository->findOneByIdentifier($identifier);
    }*/        

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
            ->setIdentifier(hash('sha1', uniqid()))
            ->setImage('/images/cartes/dames/anardil.jpg')
            ->setCreated(new \DateTime())
        ;
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}