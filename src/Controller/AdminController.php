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
    // =========================
    // Page d'accueil du tableau de bord administrateur
    // =========================
    #[Route('/admin/tableau-de-board', name: 'app_admin')]
        #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
        public function index(
            StatsCounter $counter
            ): Response
        {
             // Récupérer les statistiques
            $totals = $counter->getTotals();

            return $this->render('admin/admin-dashboard.html.twig', [
                'totals' => $totals,
            ]);
        }
    
    // =========================
    // Liste des sessions à venir
    // =========================
    #[Route('/admin/tableau-cours', name: 'app_admin_sessions')]
        #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
        public function findUpcomingSessions(): Response
        {
        
            return $this->render('admin/admin-sessions.html.twig', [
            ]);
        }

    // =========================
    // Historique des sessions passées
    // =========================
    #[Route('/admin/tableau-cours-historique', name: 'app_admin_sessions_historique')]
        #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
        public function findPastSessions(): Response
        {
            return $this->render('admin/admin-sessions-historique.html.twig', [
            ]);
        }
        
    // =========================
    // Tableau de gestion des comptes professeurs
    // =========================
    #[Route('/admin/teacher-edit', name: 'app_teacher_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
    public function teacherEdit(
        UserRepository $userRepository
        ): Response
    {
        // Récupérer tous les utilisateurs ayant le rôle ROLE_TEACHER
        $teachers = $userRepository->findByRole('ROLE_TEACHER');

        return $this->render('admin/admin-teacher-edit.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    // =========================
    // Formulaire de création d’un compte professeur par admin
    // =========================
    #[Route('/admin/teacher-nouveau', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
    public function new(
        Request $request,
        AdminService $adminService
        ): Response
    {
        // Création d’un nouvel utilisateur
        $user = new User();

        // Création du formulaire associé
        $form = $this->createForm(TeacherNewFormType::class, $user);

        // Traitement de la requête
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair
            $plainPassword = $form->get('plainPassword')->getData();
            // Création du compte professeur via le service admin
            $adminService->createTeacher($user, $plainPassword);
            // Message flash de confirmation
            $this->addFlash('success', 'Compte professeur créé.');
            // Redirection vers la liste des professeurs
            return $this->redirectToRoute(
                'app_teacher_edit', [],
                Response::HTTP_SEE_OTHER
            );
        }

        // Affichage du formulaire de création
        return $this->render('admin/admin-teacher-new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // =========================
    // Suppression d’un compte professeur par admin
    // =========================
    #[Route('/admin/teacher/{id}/delete', name: 'app_teacher_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
    public function deleteTeacher(
        Request $request,
        User $user,
        AdminService $adminService
        ): Response
    {

        // Vérification du token CSRF
        if ($this->isCsrfTokenValid(
            'delete_teacher' . $user->getId(),
            $request->getPayload()->getString('_token')
        )) {
            // Suppression du professeur via le service admin
            $adminService->deleteTeacher($user);
            // Message flash de confirmation
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        // Redirection vers la liste des professeurs
        return $this->redirectToRoute(
            'app_teacher_edit',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    // =========================
    // Tableau de gestion des élèves
    // =========================
    #[Route('/admin/student_edit', name: 'app_student_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')] // Accès réservé à l’administrateur
    public function studentEdit(UserRepository $userRepository): Response
    {
        // Récupérer uniquement les utilisateurs élèves
        $students = $userRepository->findStudentsOnly();

        return $this->render('admin/admin-student-edit.html.twig', [
            'students' => $students,
        ]);
    }

    // =========================
    // Suppression d’un compte élève par admin
    // =========================
    #[Route('/admin/student/{id}/delete', name: 'app_student_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteStudent(
        Request $request,
        User $user,
        AdminService $adminService
        ): Response
    {

        // Vérification du token CSRF
        if ($this->isCsrfTokenValid(
            'delete_student' . $user->getId(),
            $request->getPayload()->getString('_token')
        )) {
            // Suppression de l’élève via le service admin
            $adminService->deleteStudent($user);
            // Message flash de confirmation
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        // Redirection vers la liste des élèves
        return $this->redirectToRoute(
            'app_student_edit',
            [],
            Response::HTTP_SEE_OTHER);
    }

}
