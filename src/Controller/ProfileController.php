<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfileController extends AbstractController
{
    #[Route('/mon-espace', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('profile/espace-eleve.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/mon-espace/mes-cours', name: 'app_profile_cours')]
    #[IsGranted('ROLE_USER')]
    public function upcomingSession(ReservationRepository $reservation_repository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $upcoming = $reservation_repository->findUpcomingByStudent($user);



        return $this->render('profile/cours-eleve.html.twig', [
            'upcoming' => $upcoming,
        ]);
    }

    #[Route('/mon-espace/mes-historique', name: 'app_profile_historique')]
    #[IsGranted('ROLE_USER')]
    public function pastSession(ReservationRepository $reservation_repository): Response
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $past = $reservation_repository->findPastByStudent($user);


        return $this->render('profile/cours-eleve-historique.html.twig', [
            'past' => $past,
        ]);
    }


}
