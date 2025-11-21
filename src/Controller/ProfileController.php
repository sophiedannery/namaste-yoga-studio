<?php

/**
 * ProfileController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Provide the private "My Space" area for authenticated students.
 *
 * What it does:
 *   - Shows the user profile landing page.
 *   - Lists upcoming sessions reserved by the current user.
 *   - Lists past sessions (history) reserved by the current user.
 */

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
        return $this->render('profile/cours-eleve.html.twig', [
        ]);
    }

    #[Route('/mon-espace/mes-historique', name: 'app_profile_historique')]
    #[IsGranted('ROLE_USER')]
    public function pastSession(ReservationRepository $reservation_repository): Response
    {
        return $this->render('profile/cours-eleve-historique.html.twig', [
        ]);
    }

    #[Route('/mon-espace/modifier', name: 'app_profile_modifier')]
    #[IsGranted('ROLE_USER')]
    public function modifProfile(): Response
    {
        return $this->render('profile/espace-eleve-modif.html.twig', [
        ]);
    }


}
