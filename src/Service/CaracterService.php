<?php

//src/Service/CharacterService.php

namespace App\Service;

use DateTime;
use App\Entity\Caracter;
use App\Service\CaracterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CaracterRepository;
use App\Form\CaracterType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Event\CaracterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;

class CaracterService implements CaracterServiceInterface
{
    public EntityManagerInterface $em;
    private CaracterRepository $caracterRepository;
    private FormFactoryInterface $formFactory;
    private ValidatorInterface $validator;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        CaracterRepository $caracterRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em= $em;
        $this->caracterRepository = $caracterRepository;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher= $dispatcher;
    }

    /*public function findOneByIdentifier($identifier): Caracter
    {
        return $this->caracterRepository->findOneByIdentifier($identifier);
    }*/

    public function findAll(): array
    {
        return $this->caracterRepository->findAll();
    }

    public function create(string $data): Caracter
    {
        $character = new Caracter();
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreated(new \DateTime())
        ;
        $this->submit($character, CaracterType::class, $data);
        // Dispatch created event
        $event = new CaracterEvent($character);
        // Utilisation de la constante définie dans l'Event
        $this->dispatcher->dispatch($event, CaracterEvent::CHARACTER_CREATED);

        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();
        $this->dispatcher->dispatch($event, CaracterEvent::CHARACTER_CREATED_POST_DATABASE);

        return $character;
    }

    public function modify(Caracter $character, string $data): Caracter
    {
        $this->submit($character, CaracterType::class, $data);
        $event = new CaracterEvent($character);
        // Utilisation de la constante définie dans l'Event
        $this->dispatcher->dispatch($event, CaracterEvent::CHARACTER_MODIFIED);

        $this->isEntityFilled($character);

        $character
            ->setModified(new \DateTime())
        ;


        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }

    public function delete(Caracter $caracter): bool
    {
        $this->em->remove($caracter);
        $this->em->flush();
        return true;
    }

    public function submit(Caracter $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        // Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }
        // Submits form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
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

    public function isEntityFilled(Caracter $character)
    {
        // Vérification du bon fonctionnement en introduisant une erreur
        $errors = $this->validator->validate($character);

        if (count($errors) > 0) {
            $errorMsg  = $errors . 'Wrong data for Entity -> ';
            $errorMsg .= json_encode($this->serializeJson($character));
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
                        AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                            return $object->getId(); // Ce qu'il doit retourner
                        },
                    ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        $this->setLinks($object);
        return $serializer->serialize($object, 'json');
    }

    public function setLinks($object)
    {
        $links =[[
            'rel' => 'self',
            'uri' => '/caracter/' . $object->getIdentifier()
        ],[
            'rel' => 'modify',
            'uri' => '/caracter/modify/' . $object->getIdentifier()
        ],[
            'rel' => 'delete',
            'uri' => '/caracter/delete/' . $object->getIdentifier()
        ]];
        $object->setLinks($links);
        if($object instanceof SlidingPagination) {
                        // Si oui, on boucle sur les items
                        foreach ($object->getItems() as $item) {
                            $this->setLinks($item);
                        }
                       return;
                    }
    }

    public function getImages(int $number,string $kind): array
    {
        $folder = __DIR__ . '/../../public/images/';
        if($kind != ""){
            $folder.=$kind;
        }
        $finder = new Finder();
        $finder
            ->files() // On veut des fichiers
            ->in($folder) // Dans le dossier images
            ->notPath('/cartes/') // On ne veut pas les cartes
            ->sortByName() // On trie par nom
        ;
        $images = array();
        foreach ($finder as $file) {
// dump($file); // Si vous voulez voir le contenu de file
            $images[] = str_replace(__DIR__ . '/../../public', '', $file->getPathname());
        }
        shuffle($images);
        return array_slice($images, 0, $number, true);
    }
}
