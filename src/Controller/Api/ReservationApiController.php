<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/reservations', name: 'app_api_reservations_')]
final class ReservationApiController extends AbstractController
{
    // READ - réservation de l'élève connecté
    #[Route('/my', name: 'showMyReservations', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showMyReservations(
        ReservationRepository $reservation_repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    if (!$user) {return new JsonResponse(
            ['error' => 'Utilisateur non authentifié'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    // Récupérer uniquement les réservations de ce student
    $reservations = $reservation_repository->findBy(
        ['student' => $user],
    );

    $jsonReservations = $serializer->serialize($reservations, 'json', ['groups' => 'getReservations']);
    return new JsonResponse($jsonReservations, Response::HTTP_OK, [], true);
    }


    // UPDATE - annuler une réservation (setStatut CANCELLED)
    #[Route('/cancel/{id}', name: 'cancelReservation', methods: ['PATCH'])]
    #[IsGranted('ROLE_USER')]
    public function cancelReservation(
        Reservation $reservation,
        ReservationService $reservationService,
        StatsCounter $counter
    ): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $result = $reservationService->cancel($reservation, $user);

        if (!empty($result['errors'])) {
            // 403 si accès interdit, 422 si déjà annulée (tu peux affiner)
            $msg = $result['errors'][0];

            if ($msg === 'Accès interdit') {
                return new JsonResponse(['error' => $msg], Response::HTTP_FORBIDDEN);
            }

            return new JsonResponse(['errors' => $result['errors']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $counter->incCancelled(1);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    
    #[Route('/test/error', name: 'app_api_test_error', methods:['GET'])]
    public function testError(): JsonResponse
    {
        // Panne volontaire pour tester l'EsceptionSubscriber
        throw new \Exception('Erreur de test pour démonstration');
    }

}