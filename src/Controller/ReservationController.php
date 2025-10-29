<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }



    #[Route('/reservation/{id}/reserver', name: 'app_reservation_reserver', methods: ['POST'])]
    public function reserver(int $id, Request $request, SessionRepository $session_repository, EntityManagerInterface $em, ReservationRepository $reservation_repository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->isCsrfTokenValid('reserver' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Requête invalide, token CSRF non validé.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $session = $session_repository->find($id);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$session) {
            $this->addFlash('error', 'Session introuvable.');
            return $this->redirectToRoute('app_session_planning');
        }

        if ($session->getTeacher() === $user) {
            $this->addFlash('error', 'Vous ne pouvez pas participer à votre propre cours.');
            return $this->redirectToRoute('app_session_planning', ['id' => $id]);
        }

        if ((new \DateTimeImmutable('now')) >= $session->getStartAt()) {
            $this->addFlash('warning', 'Le cours a commencé : réservation impossible.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $exists = $reservation_repository->findOneBy([
            'session' => $session,
            'student' => $user
        ]);
        if ($exists && $exists->getCancelledAt() === null) {
            $this->addFlash('warning', 'Vous participez déjà à ce cours.');
            return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $active = $reservation_repository->countActiveBySession($session); 
        $remaining = $session->getCapacity() - $active;
        if ($remaining <= 0) {
        $this->addFlash('error', 'Plus de place disponible sur ce cours.');
        return $this->redirectToRoute('app_session_details', ['id' => $id]);
        }

        $reservation = new Reservation();
        $reservation 
            ->setSession($session)
            ->setStudent($user)
            ->setStatut('CONFIRMED')
            ->setBookedAt(new \DateTimeImmutable());
        $em->persist($reservation);

        $em->flush();

        $this->addFlash('success', 'Votre réservation est confirmée !');

        return $this->redirectToRoute('app_profile_cours');
    }

    #[Route('/reservation/{id}/annuler', name: 'app_reservation_annuler', methods: ['POST'])]
    public function annulerReservation(int $id, Request $request, ReservationRepository $reservation_repository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Reservation|null $reservation */
        $reservation = $reservation_repository->find($id);
        if (!$reservation) {
            $this->addFlash('error', 'Réservation introuvable.');
            return $this->redirectToRoute('app_profile_cours');
        }

        if (!$this->isCsrfTokenValid('cancel_reservation' . $reservation->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Token invalide.');
        return $this->redirectToRoute('app_profile_cours');
        }

        if ($reservation->getStudent() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
        }

        $reservation->setStatut('CANCELLED');
        $reservation->setCancelledAt(new \DateTimeImmutable('now'));
        $reservation->setCancelledBy($this->getUser());
        $reservation->setUpdatedAt(new \DateTimeImmutable('now'));

        $em->flush();

        $this->addFlash('success', 'Votre réservation a bien été annulée.');

        return $this->redirectToRoute('app_profile_cours');
    }



}
