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

class CaracterService implements CaracterServiceInterface
{
    public EntityManagerInterface $em;
    private CaracterRepository $caracterRepository;
    private FormFactoryInterface $formFactory;
    private ValidatorInterface $validator;

    public function __construct(
         EntityManagerInterface $em,
         FormFactoryInterface $formFactory,
         ValidatorInterface $validator,
         CaracterRepository $caracterRepository
            ) {
                $this->em= $em; 
                $this->caracterRepository = $caracterRepository;
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
        $characters = $this->caracterRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }
        return $charactersFinal;
    }

    public function create(string $data): Caracter
    {
        $character = new Caracter();
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreated(new \DateTime())
        ;
        $this->submit($character, CaracterType::class, $data);
        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }

    public function modify(Caracter $character,string $data): Caracter
    {
        $this->submit($character, CaracterType::class, $data);
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
            $errorMsg  = (string) $errors . 'Wrong data for Entity -> ';
            $errorMsg .= json_encode($character->toArray());
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }
}