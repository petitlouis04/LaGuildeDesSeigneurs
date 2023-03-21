<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\UserServiceInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    public function __construct(
                 UserServiceInterface $userService
            ) {
                $this->userService = $userService;
            }

    /**
     * @Route("/signin", name="app_signin", methods={"POST","HEAD"})
     */
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        if (null !== $user) {
            return $this->json([
                'token' => $this->userService->getToken($user),
            ]);
    }
    return $this->json([
                    'error' => 'User not found',
                ]);
    }
}
