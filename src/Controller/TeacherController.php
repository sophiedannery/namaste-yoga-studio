<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TeacherController extends AbstractController
{

    // =========================
    // Page principale de l’espace professeur
    // =========================
    #[Route('/teacher/espace-professeur', name: 'app_profile_teacher')]
    #[IsGranted('ROLE_TEACHER')] // Accès réservé aux professeurs
    public function index(): Response
    {
        return $this->render('teacher/espace-professeur.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }

    // =========================
    // Planning des cours à venir du professeur
    // =========================
    #[Route('/teacher/espace-professeur/planning', name: 'app_profile_teacher_planning')]
    #[IsGranted('ROLE_TEACHER')] // Accès réservé aux professeurs
    public function upComingSessionTeacher(): Response
    {
        return $this->render('teacher/cours-teacher.html.twig', [
        ]);
    }

    // =========================
    // Historique des cours du professeur
    // =========================
    #[Route('/teacher/espace-professeur/historique', name: 'app_profile_teacher_historique')]
    #[IsGranted('ROLE_TEACHER')] // Accès réservé aux professeurs
    public function pastSessionTeacher(): Response
    {
        return $this->render('teacher/cours-teacher-historique.html.twig', [
        ]);
    }
}
