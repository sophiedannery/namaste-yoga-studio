<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SessionRepository;
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
        
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        return $this->render('teacher/espace-professeur.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }


    #[Route('/espace-professeur/planning', name: 'app_profile_teacher_planning')]
    #[IsGranted('ROLE_TEACHER')]
    public function upComingSessionTeacher(SessionRepository $session_repository, ReservationRepository $reservation_repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        /** @var \App\Entity\User $teacher */
        $teacher = $this->getUser();

        $upcoming = $session_repository->findUpcomingByTeacher($teacher);

        $studentsBySession = [];
        foreach($upcoming as $sess) {
            $studentsBySession[$sess->getId()] = $reservation_repository->findActiveBySession($sess);
        }

        return $this->render('teacher/cours-teacher.html.twig', [
            'upcoming' => $upcoming,
            'studentsBySession' => $studentsBySession,
        ]);
    }


    #[Route('/espace-professeur/historique', name: 'app_profile_teacher_historique')]
    #[IsGranted('ROLE_TEACHER')]
    public function pastSessionTeacher(SessionRepository $session_repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        /** @var \App\Entity\User $teacher */
        $teacher = $this->getUser();

        $past = $session_repository->findPastByTeacher($teacher);

        return $this->render('teacher/cours-teacher-historique.html.twig', [
            'past' => $past,
        ]);
    }
}
