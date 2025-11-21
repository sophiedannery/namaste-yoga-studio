<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/users', name: 'app_api_users_')]
final class UserApiController extends AbstractController
{


    #[Route('/show', name: 'show', methods: ['GET'])]
    public function showAllUsers(UserRepository $user_repository, SerializerInterface $serializer): JsonResponse
    {
        $users = $user_repository->findAll();
        // Convertion en json
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'getUsers']);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }


    #[Route('/show/{id}', name: 'showUser', methods: ['GET'])]
    public function showDetailUser(User $user, SerializerInterface $serializer) : JsonResponse
    {
        $jsonUser = $serializer->serialize($user, 'json',  ['groups' => 'getUsers']);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);

    }

    // READ infos de l'élève connecté
    #[Route('/me', name: 'showMyProfile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showMyProfile(
        SerializerInterface $serializer
    ): JsonResponse 
    {
        // Récupérer l'utilisateur connecté 
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Utilisateur non authentifié'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Ici on sérialise directement l'utilisateur connecté
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);

        // true = le contenu est déjà du JSON encodé
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

}