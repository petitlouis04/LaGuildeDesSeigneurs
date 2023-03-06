<?php
//src/Service/CharacterService.php
namespace App\Service;
use DateTime;
use App\Entity\Player;
use App\Service\PlayerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Form\PlayerType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerService implements PlayerServiceInterface
{
    public EntityManagerInterface $em;
    private CaracterRepository $caracterRepository;
    private FormFactoryInterface $formFactory;
    private ValidatorInterface $validator;

    public function __construct(
         EntityManagerInterface $em,
         FormFactoryInterface $formFactory,
         ValidatorInterface $validator,
         PlayerRepository $playerRepository
            ) {
                $this->em= $em; 
                $this->playerRepository = $playerRepository;
                $this->formFactory = $formFactory;
                $this->validator = $validator;
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

    public function createPlayer(string $data):Player{
       $player = new Player();
       $player
              ->setIdentifier(hash('sha1', uniqid()))
              ;
        
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    public function modify(Player $player,string $data): Player
    {
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

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

    public function submit(Player $player, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        // Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }   
        // Submits form
        $form = $this->formFactory->create($formName, $player, ['csrf_protection' => false]);
        $form->submit($dataArray, false);// With false, only submitted fields are validated
        // Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg  = 'Error ' . get_class($error->getCause());
            $errorMsg .= ' --> ' . $error->getMessageTemplate();
            $errorMsg .= ' ' . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }

    public function isEntityFilled(Player $player)
    {
        $errors = $this->validator->validate($player);
        if (count($errors) > 0) {
            $errorMsg  = (string) $errors . 'Wrong data for Entity -> ';
            $errorMsg .= json_encode($player->toArray());
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }
}