<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Caracter;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CaracterRepository;
use App\Service\CaracterServiceInterface;

class CaracterController extends AbstractController
{

    public function __construct(
                CaracterServiceInterface $caracterService
                 
            ) {

            }

    /**
     * @Route("/caracter/display", name="app_caracter", methods={"GET","HEAD"})
     */
    public function display(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Caracter::class);
        dd($repository->findAll());
    
        return new JsonResponse($character->toArray());
    }

    /**
     * @Route("/caracter/create", name="app_caracter", methods={"GET","HEAD"})
     */
    public function create(ManagerRegistry $doctrine): JsonResponse
    {
        // Reprendre les données de src/Entity/Character.php
        $character = $this->caracterService->create();
        return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    }

}
