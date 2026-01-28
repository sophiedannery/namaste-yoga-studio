<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\TeacherNewFormType;
use App\Repository\UserRepository;
use App\Service\AdminService;
use App\Stats\StatsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{

    // Page d'accueil du tableau de bord Admin. 
    #[Route('/admin/tableau-de-board', name: 'app_admin')]
        #[IsGranted('ROLE_ADMIN')]
        public function index(
            StatsCounter $counter
            ): Response
        {
            $totals = $counter->getTotals();
            return $this->render('admin/admin-dashboard.html.twig', [
                'totals' => $totals,
            ]);
        }

    
    // Liste des sessions à venir
    #[Route('/admin/tableau-cours', name: 'app_admin_sessions')]
        #[IsGranted('ROLE_ADMIN')]
        public function findUpcomingSessions(): Response
        {
            return $this->render('admin/admin-sessions.html.twig', [
            ]);
        }


    // Historique des sessions
    #[Route('/admin/tableau-cours-historique', name: 'app_admin_sessions_historique')]
        #[IsGranted('ROLE_ADMIN')]
        public function findPastSessions(): Response
        {
            return $this->render('admin/admin-sessions-historique.html.twig', [
            ]);
        }

    

        
    // Tableau des professeurs
    #[Route('/admin/teacher-edit', name: 'app_teacher_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function teacherEdit(
        UserRepository $userRepository
        ): Response
    {
        $teachers = $userRepository->findByRole('ROLE_TEACHER');
        return $this->render('admin/admin-teacher-edit.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    // Formulaire d'ajout d'un professeur
    #[Route('/admin/teacher-nouveau', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(
        Request $request, 
        AdminService $adminService
        ): Response
    {
        $user = new User();
        $form = $this->createForm(TeacherNewFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $adminService->createTeacher($user, $plainPassword);
            $this->addFlash('success', 'Compte professeur créé.');
            return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin-teacher-new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // Suppression d'un professeur
    #[Route('/admin/teacher/{id}/delete', name: 'app_teacher_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteTeacher(
        Request $request, 
        User $user, 
        AdminService $adminService
        ): Response
    {
        if ($this->isCsrfTokenValid('delete_teacher' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $adminService->deleteTeacher($user);
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
    }

    // Tableau des élèves
    #[Route('/admin/student_edit', name: 'app_student_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function studentEdit(UserRepository $userRepository): Response
    {
        $students = $userRepository->findStudentsOnly();
        return $this->render('admin/admin-student-edit.html.twig', [
            'students' => $students,
        ]);
    }

    // Suppression d'un élève
    #[Route('/admin/student/{id}/delete', name: 'app_student_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteStudent(
        Request $request, 
        User $user, 
        AdminService $adminService
        ): Response
    {
        if ($this->isCsrfTokenValid('delete_student' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $adminService->deleteStudent($user);
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_student_edit', [], Response::HTTP_SEE_OTHER);
    }

    
}
