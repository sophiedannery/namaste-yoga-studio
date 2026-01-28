<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TeacherController extends AbstractController
{
    #[Route('/espace-professeur', name: 'app_profile_teacher')]
    #[IsGranted('ROLE_TEACHER')]
    public function index(): Response
    {
        return $this->render('teacher/espace-professeur.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }

    #[Route('/espace-professeur/planning', name: 'app_profile_teacher_planning')]
    #[IsGranted('ROLE_TEACHER')]
    public function upComingSessionTeacher(): Response
    {
        return $this->render('teacher/cours-teacher.html.twig', [
            
        ]);
    }

    #[Route('/espace-professeur/historique', name: 'app_profile_teacher_historique')]
    #[IsGranted('ROLE_TEACHER')]
    public function pastSessionTeacher(): Response
    {
        return $this->render('teacher/cours-teacher-historique.html.twig', [
        ]);
    }
}
