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
     * @Route("/caracter/{identifier}",
     *  name="app_caracter_display",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"GET","HEAD"})
     * @ParamConverter("caracter")
     */
    public function display(Caracter $caracter): JsonResponse
    {

        $this->denyAccessUnlessGranted('characterDisplay', $caracter);
        return new JsonResponse($caracter->toArray());
    }

    /**
     * @Route("/create", name="app_caracter_create", methods={"POST","HEAD"})
     */
    public function create(): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterCreate', null);
        $caracter = $this->caracterIntercace->create();
        return new JsonResponse($caracter->toArray(), JsonResponse::HTTP_CREATED);
    }

    /** 
     * @Route("/caracter/modify/{identifier}",
     *  name="app_character_modify",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"PUT","HEAD"})
     */
    public function modify(Caracter $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->caracterService->modify($character);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /** 
     * @Route("/caracter/delete/{identifier}",
     *  name="app_character_delete",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"DELETE","HEAD"})
     */
    public function delete(Caracter $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $character = $this->caracterService->delete($character);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/caracter/index", name="app_caracter_index", methods={"GET","HEAD"})
     */
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->caracterService->findAll();
        return new JsonResponse($characters);
    }

    /** 
     * @Route("/caracter", name="app_character_redirect_index", methods={"GET","HEAD"})
     */
    public function redirectIndex()
    {
        return $this->redirectToRoute('app_caracter_index');
    }
}
