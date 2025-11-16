<?php

namespace App\Controller\Api;

use App\Entity\Session;
use App\Repository\ClassTypeRepository;
use App\Repository\RoomRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/sessions', name: 'app_api_sessions_')]
final class SessionApiController extends AbstractController
{

    // CREATE - créer une session 
    #[Route('/create', name: 'createSession', methods: ['POST'])]
    public function createSessions(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $em, 
        UrlGeneratorInterface $urlGenerator, 
        UserRepository $user_repository,
        ClassTypeRepository $classType_repository,
        RoomRepository $room_repository
        ): JsonResponse
    {
        $session = $serializer->deserialize($request->getContent(), Session::class, 'json');

        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();

        // Récupération des id. S'ils ne sont pas défini, alors on met -1 par défaut
        $idTeacher = $content['idTeacher'] ?? -1;
        $idClassType = $content['idClassType'] ?? -1;
        $idRoom = $content['idRoom'] ?? -1;

        // On chercher le teacher qui correspond et on l'assigne à la session
        $session->setTeacher($user_repository->find($idTeacher));
        $session->setClassType($classType_repository->find($idClassType));
        $session->setRoom($room_repository->find($idRoom));


        $em->persist($session);
        $em->flush();

        $jsonSession = $serializer->serialize($session, 'json', ['groups' => 'getSessions']);

        //calculer la location pour le header
        $location = $urlGenerator->generate('app_api_sessions_showSession', ['id' => $session->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSession, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    // READ - toutes les sessions
    #[Route('/show', name: 'showSessions', methods: ['GET'])]
    public function showAllSessions(SessionRepository $session_repository, SerializerInterface $serializer): JsonResponse
    {
        $sessions = $session_repository->findAll();
        // Convertion en json
        $jsonSessions = $serializer->serialize($sessions, 'json', ['groups' => 'getSessions']);

        return new JsonResponse($jsonSessions, Response::HTTP_OK, [], true);
    }

    // READ - une session 
    #[Route('/show/{id}', name: 'showSession', methods: ['GET'])]
    public function showDetailSession(Session $session, SerializerInterface $serializer) : JsonResponse
    {
        $jsonSession = $serializer->serialize($session, 'json',  ['groups' => 'getSessions']);
        return new JsonResponse($jsonSession, Response::HTTP_OK, [], true);
    }

    // UPDATE - modifier une session
    #[Route('/update/{id}', name: 'updateSession', methods: ['PUT'])]
    public function updateSession(
        Request $request, 
        SerializerInterface $serializer,
        Session $current_session, 
        EntityManagerInterface $em,
        UserRepository $user_repository,
        ClassTypeRepository $classType_repository,
        RoomRepository $room_repository
        ): JsonResponse
    {
        $updatedSession = $serializer->deserialize($request->getContent(),
        Session::class,
        'json',
        [AbstractNormalizer::OBJECT_TO_POPULATE => $current_session]);

        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();
        // Récupération des id. S'ils ne sont pas défini, alors on met -1 par défaut
        $idTeacher = $content['idTeacher'] ?? -1;
        $idClassType = $content['idClassType'] ?? -1;
        $idRoom = $content['idRoom'] ?? -1;
        // On chercher le teacher qui correspond et on l'assigne à la session
        $updatedSession->setTeacher($user_repository->find($idTeacher));
        $updatedSession->setClassType($classType_repository->find($idClassType));
        $updatedSession->setRoom($room_repository->find($idRoom)); 

        $em->persist($updatedSession);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    // UPDATE - annuler une session
    #[Route('/cancel/{id}', name: 'cancelSession', methods: ['PATCH'])]
    public function cancelSession(Session $session, EntityManagerInterface $em): JsonResponse
    {
        // Tu adaptes la valeur selon ce que tu utilises en BDD : CANCELLED, canceled, ANNULÉ, etc.
        $session->setStatus('CANCELLED');

        $em->flush();

        // 204 = pas de contenu, juste "ok"
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }



    //DELETE - supprimer une session
    #[Route('/delete/{id}', name: 'deleteSession', methods: ['DELETE'])]
    public function deleteSession(Session $session, EntityManagerInterface $em): JsonResponse {
        $em->remove($session);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}