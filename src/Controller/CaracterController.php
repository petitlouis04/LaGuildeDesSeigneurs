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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Knp\Component\Pager\PaginatorInterface;


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
     * Entity={'caracter', expr: 'repository.findOneByIdentifier(identifier)'}
     * Parametre={name: 'identifier',in: 'path',description: 'Identifier for the Character',schema: new OA\Schema(type: 'string'),required: true}
     * Response={response:200,description:'Identifier for the caracter',content:new Model(type: Caracter::class)}
     * Response={response:403,description:'Acces denied'}
     * Response={response:404,description:'Not found'}
     */
    public function display(Caracter $caracter): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDisplay', $caracter);
        return JsonResponse::fromJsonString($this->caracterService->serializeJson($caracter));
    }

    /**
     * @Route("/caracter/create", name="app_caracter_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterCreate', null);
        $caracter = $this->caracterIntercace->create($request->getContent());
        return JsonResponse::fromJsonString($this->caracterService->serializeJson($caracter), JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/caracter/modify/{identifier}",
     *  name="app_character_modify",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"PUT"})
     */
    public function modify(Request $request, Caracter $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->caracterService->modify($character, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/caracter/delete/{identifier}",
     *  name="app_character_delete",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"DELETE"})
     */
    public function delete(Caracter $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $character = $this->caracterService->delete($character);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/caracter/index", name="app_caracter_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $paginator->paginate(
                        $this->caracterService->findAll(), // On appelle la même requête
                        $request->query->getInt('page', 1), // 1 par défaut
                        min(100, $request->query->getInt('size', 10)) // 10 par défaut et 100 maximum
                    );
                    return JsonResponse::fromJsonString($this->caracterService->serializeJson($characters));
    }

    /**
     * @Route("/caracter", name="app_character_redirect_index", methods={"GET"})
     */
    public function redirectIndex()
    {
        return $this->redirectToRoute('app_caracter_index');
    }

    /**
     * @Route("/caracter/images/{number}", name="app_character_images",
     * requirements={"number"="^([0-9]{1,2})$"}, methods={"GET"})
     */
    public function images(int $number = 1)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $images = $this->caracterService->getImages($number,"");
        return new JsonResponse($images);
    }

    /**
     * @Route("/caracter/images/{kind}/{number}", name="app_character_images_kind",
     * requirements={"number"="^([0-9]{1,2})$","kind"="^(dames|seigneurs|ennemis|ennemies)$"}, methods={"GET"})
     */
    public function imagesKind(int $number = 1,string $kind="")
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $images = $this->caracterService->getImages($number,$kind);
        return new JsonResponse($images);
    }

    /**
     * @Route("/caracter/intelligence/{intelligence}",
     *  name="app_character_images_kind",
     *  methods={"GET"})
     */
    public function getIntelligence(int $intelligence){
        $character = $this->caracterService->getIntelligence($intelligence);
        return new JsonResponse($character);
    }

    
}
