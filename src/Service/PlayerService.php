<?php
//src/Service/CharacterService.php
namespace App\Service;
use DateTime;
use App\Entity\Player;
use App\Service\PlayerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;

class PlayerService implements PlayerServiceInterface
{
    public EntityManagerInterface $em;
    private CaracterRepository $caracterRepository;

    public function __construct(
         EntityManagerInterface $em,
         PlayerRepository $playerRepository
            ) {
                $this->em= $em; 
                $this->playerRepository = $playerRepository;
            }

    /*public function findOneByIdentifier($identifier): Caracter
    {
        return $this->caracterRepository->findOneByIdentifier($identifier);
    }*/  

    public function findAll(): array
    {
        $charactersFinal = array();
        $characters = $this->playerRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }
        return $charactersFinal;
    }

    public function createPlayer():Player{
       $player = new Player();
       $player->setFirstname("Aurelien")
              ->setLastName("Fillion")
              ->setEmail("Aurelien@yahoo.com")
              ->setMirian(1)
              ->setIdentifier(hash('sha1', uniqid()))
              ;
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    public function modify(Player $player): Player
    {
        $player->setFirstname("Louis")
              ->setLastName("Ancel")
              ->setEmail("Aurelien@yahoo.com")
              ->setMirian(1)
        ;
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    public function delete(Player $player): bool
    {
        $this->em->remove($player);
        $this->em->flush();
        return true;
    }
}