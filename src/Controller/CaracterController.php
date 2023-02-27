<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Caracter;
use App\Repository\CaracterRepository;
use App\Service\CaracterServiceInterface;


class CaracterController extends AbstractController
{

    public CaracterServiceInterface $caracter;
    public function __construct(
        CaracterServiceInterface $caracter
         
    ) {
        $this->caracter = $caracter;
    }

    /**
     * @Route("/caracter", name="app_caracter", methods={"GET","HEAD"})
     */
    public function display(): JsonResponse
    {

        $repository = $this->getDoctrine()->getRepository(Caracter::class);
        dd($repository->findAll());
        return $this->json();
    }

    /**
     * @Route("/create", name="app_caracter_create", methods={"POST","HEAD"})
     */
    public function create(): JsonResponse
    {
        $caracter = $this->caracter->create();
        return new JsonResponse($caracter->toArray(), JsonResponse::HTTP_CREATED);
    }
}
