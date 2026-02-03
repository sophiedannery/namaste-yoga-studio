<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/users', name: 'app_api_users_')]
final class UserApiController extends AbstractController
{

    // =========================
    // READ - afficher le profil de l'utilisateur connecté
    // =========================
    #[Route('/me', name: 'showMyProfile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')] // Accessible uniquement à un utilisateur connecté
    public function showMyProfile(
        SerializerInterface $serializer
    ): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        //Si aucun utilisateur n'est connecté
        if (!$user) {
            return new JsonResponse(
                ['error' => 'Utilisateur non authentifié'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Sérialisation de l'utilisateur connecté avec le gourpe "getUSers"
        $jsonUser = $serializer->serialize(
            $user, 'json',
            ['groups' => 'getUsers']
        );

        // Retourner le profil de l'utilisateur au format JSON
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

}