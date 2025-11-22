<?php

/**
 * AdminController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Back-office actions for administrators of Namaste Yoga Studio.
 *   Provides dashboards and CRUD-like operations on teachers, students, and sessions.
 *
 * Key responsibilities:
 *   - Display overall stats on the admin dashboard.
 *   - List / create / delete teacher accounts.
 *   - List / delete student accounts.
 *   - List / delete sessions .
 *
 */

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use App\Form\TeacherNewFormType;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Stats\StatsCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{



    /**
     * Admin dashboard showing statistics.
     * 
     * GET /admin/tableau-de-board
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/tableau-de-board', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(StatsCounter $counter): Response
    {
        // Fetch all precomputed totals (users count, sessions count, etc.).
        $totals = $counter->getTotals();

        return $this->render('admin/admin-dashboard.html.twig', [
            'totals' => $totals,
        ]);
    }

    /**
     * List all teachers.
     *
     * GET /admin/teacher-edit
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/teacher-edit', name: 'app_teacher_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function teacherEdit(UserRepository $userRepository): Response
    {
        // Custom finder that returns only users having ROLE_TEACHER.
        $teachers = $userRepository->findByRole('ROLE_TEACHER');

        return $this->render('admin/admin-teacher-edit.html.twig', [
            'teachers' => $teachers,
        ]);
    }

 /**
     * Create a new teacher account.
     *
     * GET|POST /admin/teacher-nouveau
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/teacher-nouveau', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        // Build and handle the form bound to the new User entity.
        $form = $this->createForm(TeacherNewFormType::class, $user);
        $form->handleRequest($request);
        // Validate only after submission
        if ($form->isSubmitted() && $form->isValid()) {
            // 1) Hash the plain password coming from the form field.
            $plainPassword = $form->get('plainPassword')->getData();
            $hashed = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashed);
            // 2) Assign the proper role for a teacher.
            $user->setRoles(['ROLE_TEACHER']);
             // 3) Persist and flush to save the new teacher.
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin-teacher-new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * Delete a teacher account.
     *
     * POST /admin/teacher/{id}/delete
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/teacher/{id}/delete', name: 'app_teacher_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteTeacher(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Validate CSRF token
        if ($this->isCsrfTokenValid('delete_teacher' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * List all students.
     *
     * GET /admin/student_edit
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/student_edit', name: 'app_student_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function studentEdit(UserRepository $userRepository): Response
    {
        // Custom finder returning only student accounts
        $students = $userRepository->findStudentsOnly();
        return $this->render('admin/admin-student-edit.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * Delete a student account (POST only with CSRF protection).
     *
     * POST /admin/student/{id}/delete
     * Requires ROLE_ADMIN.
     */
    #[Route('/admin/student/{id}/delete', name: 'app_student_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteStudent(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Same CSRF pattern as teachers, but with a different token id.
        if ($this->isCsrfTokenValid('delete_student' . $user->getId(), $request->getPayload()->getString('_token'))) {

            $entityManager->remove($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_student_edit', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/admin/tableau-cours', name: 'app_admin_sessions')]
    #[IsGranted('ROLE_ADMIN')]
    public function findUpcomingSessions(): Response
    {
        return $this->render('admin/admin-sessions.html.twig', [
        ]);
    }


    #[Route('/admin/tableau-cours-historique', name: 'app_admin_sessions_historique')]
    #[IsGranted('ROLE_ADMIN')]
    public function findPastSessions(): Response
    {
        return $this->render('admin/admin-sessions-historique.html.twig', [
        ]);
    }

    

}
