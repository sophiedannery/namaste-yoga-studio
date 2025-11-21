<?php

namespace App\Controller\Api;

use App\Entity\Session;
use App\Repository\ClassTypeRepository;
use App\Repository\ReservationRepository;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/sessions', name: 'app_api_sessions_')]
final class SessionApiController extends AbstractController
{

    

    // READ - toutes les sessions
    #[Route('/show', name: 'showSessions', methods: ['GET'])]
    public function showAllSessions(SessionRepository $session_repository, SerializerInterface $serializer): JsonResponse
    {
        $sessions = $session_repository->findAll();
        // Convertion en json
        $jsonSessions = $serializer->serialize($sessions, 'json', ['groups' => 'getSessions']);

        return new JsonResponse($jsonSessions, Response::HTTP_OK, [], true);
    }





    // // READ - une session 
    // #[Route('/show/{id}', name: 'showSession', methods: ['GET'])]
    // public function showDetailSession(Session $session, SerializerInterface $serializer) : JsonResponse
    // {
    //     $jsonSession = $serializer->serialize($session, 'json',  ['groups' => 'getSessions']);
    //     return new JsonResponse($jsonSession, Response::HTTP_OK, [], true);
    // }







    // READ - sessions du professeur connecté
    #[Route('/my', name: 'showMySessions', methods: ['GET'])]
    #[IsGranted('ROLE_TEACHER')]
    public function showMySessions(
        SessionRepository $session_repository,
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

    // Récupérer uniquement les sessions de ce teacher
    $sessions = $session_repository->findBy(
        ['teacher' => $user],
        ['startAt' => 'ASC'] // tri par date croissante
    );

    $jsonSessions = $serializer->serialize($sessions, 'json', ['groups' => 'getSessions']);

    return new JsonResponse($jsonSessions, Response::HTTP_OK, [], true);
    }






    // READ - élèves d'une session
    #[Route('/{id}/students', name: 'session_students', methods: ['GET'])]
    public function getSessionStudents(
        Session $session,
        ReservationRepository $reservasion_repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        // On récupère uniquement les réservations CONFIRMED pour cette session
        $reservations = $reservasion_repository->findBy([
            'session' => $session,
            'statut'  => 'CONFIRMED',
        ]);

        $students = [];
        foreach ($reservations as $reservation) {
            $student = $reservation->getStudent();
            if ($student) {
                $students[] = $student;
            }
        }

        // Sérialisation des élèves
        $json = $serializer->serialize($students, 'json', ['groups' => 'getUsers']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }






    

    // UPDATE - annuler une session
    #[Route('/cancel/{id}', name: 'cancelSession', methods: ['PATCH'])]
    #[IsGranted('ROLE_TEACHER')]
    public function cancelSession(Session $session, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if ($session->getTeacher() !== $user) {
            return new JsonResponse(
                ['error' => 'Accès interdit'],
                Response::HTTP_FORBIDDEN
            );
        }

        $session->setStatus('CANCELLED');
        $session->setCancelledAt(new \DateTimeImmutable());
        $session->setCancelledBy($user);

        $em->flush();

        // 204 = pas de contenu, juste "ok"
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    



    // //DELETE - supprimer une session
    // #[Route('/delete/{id}', name: 'deleteSession', methods: ['DELETE'])]
    // public function deleteSession(Session $session, EntityManagerInterface $em): JsonResponse {
    //     $em->remove($session);
    //     $em->flush();
    //     return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    // }
}