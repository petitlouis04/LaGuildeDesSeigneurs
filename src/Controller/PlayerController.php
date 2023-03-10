<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player;
use App\Service\PlayerService;
use App\Service\PlayerServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Knp\Component\Pager\PaginatorInterface;

class PlayerController extends AbstractController
{
    public PlayerService $playerService;
    public PlayerServiceInterface $playerServiceInterface;
    public function __construct(PlayerService $playerService, PlayerServiceInterface $playerServiceInterface)
    {
        $this->playerService = $playerService;
        $this->playerServiceInterface = $playerServiceInterface;
    }
    /**
     * @Route("/player/display", name="app_player_display",methods={"GET","HEAD"})
     */
    public function displayPlayer(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        $players = $paginator->paginate(
                        $this->playerService->findAll(),
                        $request->query->getInt('page', 1),
                        min(100, $request->query->getInt('size', 10))
                    );
        return JsonResponse::fromJsonString($this->playerService->serializeJson($players));
    }

    /**
     * @Route("/player/display/{identifier}",
     *  name="app_player_display_one",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"GET","HEAD"})
     * Entity={'player', expr: 'repository.findOneByIdentifier(identifier)'}
     */
    public function displayOnePlayer(Player $player): JsonResponse
    {
        //$this->denyAccessUnlessGranted('playerDisplay', $player);
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    /**
     * @Route("/player/create", name="app_player_create", methods={"POST","HEAD"})
     */
    public function create(Request $request): JsonResponse
    {
        //$this->denyAccessUnlessGranted('playerCreate', null);
        $player = $this->playerServiceInterface->createPlayer($request->getContent());
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player), JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/player/delete/{identifier}",
     *  name="app_player_delete",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"DELETE","HEAD"})
     */
    public function delete(Player $player): JsonResponse
    {
        //$this->denyAccessUnlessGranted('playerDelete', $player);
        $player = $this->playerService->delete($player);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/player/modify/{identifier}",
     *  name="app_player_modify",
     * requirements={"identifier"="^([a-z0-9]{40})$"},
     *  methods={"PUT","HEAD"})
     */
    public function modify(Request $request, Player $player): JsonResponse
    {
        //$this->denyAccessUnlessGranted('playerModify', $player);
        $player = $this->playerService->modify($player, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
