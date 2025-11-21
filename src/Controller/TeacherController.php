<?php

/**
 * TeacherController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Provide the teacher's private area: dashboard, upcoming sessions with students,
 *   and past sessions (history).
 *
 */

namespace App\Controller;

use App\Form\SessionForm;
use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TeacherController extends AbstractController
{

    /**
     * Teacher dashboard / landing page.
     *
     * GET /espace-professeur
     * Requires ROLE_TEACHER.
     */
    #[Route('/espace-professeur', name: 'app_profile_teacher')]
    #[IsGranted('ROLE_TEACHER')]
    public function index(): Response
    {
    
        return $this->render('teacher/espace-professeur.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }


    /**
     * List upcoming sessions for the logged-in teacher, with enrolled students.
     *
     * GET /espace-professeur/planning
     * Requires ROLE_TEACHER.
     */
    #[Route('/espace-professeur/planning', name: 'app_profile_teacher_planning')]
    #[IsGranted('ROLE_TEACHER')]
    public function upComingSessionTeacher(): Response
    {
        
        return $this->render('teacher/cours-teacher.html.twig', [
            
        ]);
    }

    

    /**
     * List past sessions (history) for the logged-in teacher.
     *
     * GET /espace-professeur/historique
     * Requires ROLE_TEACHER.
     */
    #[Route('/espace-professeur/historique', name: 'app_profile_teacher_historique')]
    #[IsGranted('ROLE_TEACHER')]
    public function pastSessionTeacher(): Response
    {
        return $this->render('teacher/cours-teacher-historique.html.twig', [
        ]);
    }


}
