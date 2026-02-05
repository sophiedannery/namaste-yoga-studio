<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfileController extends AbstractController
{
    // =========================
    // Page principale de l’espace élève
    // =========================
    #[Route('/profile/mon-espace', name: 'app_profile')]
    #[IsGranted('ROLE_USER')] // Accessible uniquement aux utilisateurs connectés
    public function index(): Response
    {
        return $this->render('profile/espace-eleve.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    // =========================
    // Liste des cours à venir de l’élève
    // =========================
    #[Route('/profile/mon-espace/mes-cours', name: 'app_profile_cours')]
    #[IsGranted('ROLE_USER')] // Accessible uniquement aux utilisateurs connectés
    public function upcomingSession(): Response
    {
        return $this->render('profile/cours-eleve.html.twig', [
        ]);
    }


    // =========================
    // Historique des cours suivis par l’élève
    // =========================
    #[Route('/profile/mon-espace/mes-historique', name: 'app_profile_historique')]
    #[IsGranted('ROLE_USER')] // Accessible uniquement aux utilisateurs connectés
    public function pastSession(): Response
    {
        return $this->render('profile/cours-eleve-historique.html.twig', [
        ]);
    }

    // =========================
    // Modification du profil de l’élève
    // =========================
    #[Route('/profile/mon-espace/modifier', name: 'app_profile_modifier')]
    #[IsGranted('ROLE_USER')] // Accessible uniquement aux utilisateurs connectés
    public function modifProfile(): Response
    {
        return $this->render('profile/espace-eleve-modif.html.twig', [
        ]);
    }
}
