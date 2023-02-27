<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Caracter;
use App\Repository\CaracterRepository;
use App\Service\CaracterServiceInterface;
use App\Service\CaracterService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CaracterController extends AbstractController
{

    public CaracterServiceInterface $caracterIntercace;
    public CaracterService $caracterService;

    public function __construct(
        CaracterServiceInterface $caracterIntercace,
        CaracterService $caracterService
         
    ) {
        $this->caracterIntercace = $caracterIntercace;
        $this->caracterService = $caracterService;
    }

    /**
     * 
     * @Route("/caracter/{identifier}",
     *  name="app_caracter_display",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"GET","HEAD"})
     * @ParamConverter("caracter")
     */
    public function display(Caracter $caracter): JsonResponse
    {

        //$caracter = $this->caracterService->findOneByIdentifier($identifier);
        //dd($caracter);
        return new JsonResponse($caracter->toArray());
    }

    /**
     * @Route("/create", name="app_caracter_create", methods={"POST","HEAD"})
     */
    public function create(): JsonResponse
    {
        $caracter = $this->caracterIntercace->create();
        return new JsonResponse($caracter->toArray(), JsonResponse::HTTP_CREATED);
    }
}
