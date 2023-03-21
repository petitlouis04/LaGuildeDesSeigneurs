<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    /**
     * @Route("/signin", name="app_signin", methods={"POST","HEAD"})
     */
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUserIdentifier(), // Voir classe User
            'roles' => $user->getRoles(),
        ]);
    }
}
