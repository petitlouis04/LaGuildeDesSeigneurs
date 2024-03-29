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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Event\PlayerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class PlayerService implements PlayerServiceInterface
{
    public EntityManagerInterface $em;
    private FormFactoryInterface $formFactory;
    private ValidatorInterface $validator;
    private EventDispatcherInterface $dispatcher;
    private PlayerRepository $playerRepository;

    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        PlayerRepository $playerRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em= $em;
        $this->playerRepository = $playerRepository;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher= $dispatcher;
    }

    /*public function findOneByIdentifier($identifier): Caracter
    {
        return $this->caracterRepository->findOneByIdentifier($identifier);
    }*/
    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
                        AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                            return $object->getId(); // Ce qu'il doit retourner
                        },
                    ];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizers = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        $this->setLinks($object);

        $context = (new ObjectNormalizerContextBuilder())
             ->withGroups(['player'])
             ->toArray()
         ;

         return $serializer->serialize($object, 'json', $context);
    }

    public function findAll(): array
    {
        return $this->playerRepository->findAll();
    }

    public function createPlayer(string $data): Player
    {
        $player = new Player();
        $player
               ->setIdentifier(hash('sha1', uniqid()))
        ;

        $this->submit($player, PlayerType::class, $data);

        $event = new PlayerEvent($player);
        $this->dispatcher->dispatch($event, PlayerEvent::PLAYER_CREATED);

        $this->isEntityFilled($player);

        $this->em->persist($player);
        $this->em->flush();
        $this->dispatcher->dispatch($event, PlayerEvent::PLAYER_CREATED_POST_DATABASE);
        return $player;
    }

    public function modify(Player $player, string $data): Player
    {
        $this->submit($player, PlayerType::class, $data);
        $event = new PlayerEvent($player);
        $this->dispatcher->dispatch($event, PlayerEvent::PLAYER_MODIFIED);
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
            $errorMsg .= json_encode($this->serializeJson($player));
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function setLinks($object)
    {
        if($object instanceof SlidingPagination) {
            foreach ($object->getItems() as $item) {
                $this->setLinks($item);
            }
            return;
        }
        $links =[[
            'rel' => 'self',
            'uri' => '/player/display/' . $object->getIdentifier()
        ],[
            'rel' => 'modify',
            'uri' => '/player/modify/' . $object->getIdentifier()
        ],[
            'rel' => 'delete',
            'uri' => '/player/delete/' . $object->getIdentifier()
        ]];
        $object->setLinks($links);
        
    }
}
