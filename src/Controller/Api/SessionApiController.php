<?php

namespace App\Controller\Api;

use App\Entity\Session;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
use App\Service\SessionService;
use App\Stats\StatsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/sessions', name: 'app_api_sessions_')]
final class SessionApiController extends AbstractController
{

    // =========================
    // READ - afficher toutes les sessions
    // =========================
    #[Route('/show', name: 'showSessions', methods: ['GET'])]
    public function showAllSessions(
        SessionRepository $session_repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        // Récupérer toutes les sessions depuis la base de données
        $sessions = $session_repository->findAll();
        
        // Conversion des sessions en JSON avec le groupe de sérialisation "getSessions"
        $jsonSessions = $serializer->serialize(
            $sessions,
            'json',
            ['groups' => 'getSessions']
        );

        // Retourner la réponse JSON
        return new JsonResponse($jsonSessions, Response::HTTP_OK, [], true);
    }


    // =========================
    // READ - sessions du professeur connecté
    // =========================
    #[Route('/my', name: 'showMySessions', methods: ['GET'])]
    #[IsGranted('ROLE_TEACHER')] // Accessible uniquement aux professeurs
    public function showMySessions(
        SessionRepository $session_repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Si aucun utilisateur n’est connecté, retourner une erreur 401
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

        // Sérialisation des sessions
        $jsonSessions = $serializer->serialize(
            $sessions,
            'json',
            ['groups' => 'getSessions']
        );

        // Retourner la liste des sessions au format JSON
        return new JsonResponse($jsonSessions, Response::HTTP_OK, [], true);
    }


    // =========================
    // READ - élèves inscrits à une session
    // =========================
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

        // Tableau qui contiendra les élèves
        $students = [];

        // Parcourir les réservations pour récupérer les élèves associés
        foreach ($reservations as $reservation) {
            $student = $reservation->getStudent();
            if ($student) {
                $students[] = $student;
            }
        }

        // Sérialisation des élèves
        $json = $serializer->serialize(
            $students,
            'json',
            ['groups' => 'getUsers']
        );

        // Retourner la liste des élèves
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    // =========================
    // UPDATE - annuler une session
    // =========================
    #[Route('/cancel/{id}', name: 'cancelSession', methods: ['PATCH'])]
    #[IsGranted('ROLE_TEACHER')] // Seul le professeur peut annuler
    public function cancelSession(
        Session $session,
        SessionService $sessionService,
        StatsCounter $counter
        ): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Appeler le service pour annuler la session
        $result = $sessionService->cancel($session, $user);

        // Si l’annulation a réussi, décrémenter le compteur 
        if ($result['errors'] === []) {
            $counter->decCreated(1);
        }

        // Si des erreurs sont présentes
        if (!empty($result['errors'])) {
            $msg = $result['errors'][0];

            // Erreur d’accès
            if ($msg === 'Accès interdit') {
                return new JsonResponse(
                    ['error' => $msg],
                    Response::HTTP_FORBIDDEN
                );
            }

            // Autres erreurs métier
            return new JsonResponse(
                ['errors' => $result['errors']],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Retourner une réponse vide (statut 204)
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}