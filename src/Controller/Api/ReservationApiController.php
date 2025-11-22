<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
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

    if (!$user) {
        return new JsonResponse(
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
        EntityManagerInterface $em,
        StatsCounter $counter
        ): JsonResponse
    {
        $user = $this->getUser();

        // Vérifier que c’est bien la réservation de l’utilisateur connecté
        if ($reservation->getStudent() !== $user) {
            return new JsonResponse(
                ['error' => 'Accès interdit'],
                Response::HTTP_FORBIDDEN
            );
        }

        $reservation->setStatut('CANCELLED');
        $reservation->setCancelledAt(new \DateTimeImmutable());
        $reservation->setUpdatedAt(new \DateTimeImmutable());
        $reservation->setCancelledBy($user);

        // Update stats 
        $counter->incCancelled(1);

        $em->flush();

        // 204 = pas de contenu, juste "ok"
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/test/error', name: 'app_api_test_error', methods:['GET'])]
    public function testError(): JsonResponse
    {
        // Panne volontaire pour tester l'EsceptionSubscriber
        throw new \Exception('Erreur de test pour démonstration');
    }

}