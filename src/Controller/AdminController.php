<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use App\Form\TeacherNewFormType;
use App\Form\TeacherNewFormTypeForm;
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
    #[Route('/admin/tableau-de-board', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(StatsCounter $counter): Response
    {
        $totals = $counter->getTotals();

        return $this->render('admin/admin-dashboard.html.twig', [
            'totals' => $totals,
        ]);
    }

    #[Route('/admin/teacher-edit', name: 'app_teacher_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function teacherEdit(UserRepository $userRepository): Response
    {
        $teachers = $userRepository->findByRole('ROLE_TEACHER');

        return $this->render('admin/admin-teacher-edit.html.twig', [
            'teachers' => $teachers,
        ]);
    }


    #[Route('/admin/teacher-nouveau', name: 'app_teacher_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(TeacherNewFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            $hashed = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashed);

            $user->setRoles(['ROLE_TEACHER']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin-teacher-new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/admin/teacher/{id}/delete', name: 'app_teacher_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteTeacher(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_teacher' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_teacher_edit', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/student_edit', name: 'app_student_edit', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function studentEdit(UserRepository $userRepository): Response
    {
        $students = $userRepository->findStudentsOnly();

        return $this->render('admin/admin-student-edit.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/admin/student/{id}/delete', name: 'app_student_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteStudent(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_student' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été supprimé.');
        }

        return $this->redirectToRoute('app_student_edit', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/admin/tableau-cours', name: 'app_admin_sessions')]
    #[IsGranted('ROLE_ADMIN')]
    public function findSessions(SessionRepository $session_repository): Response
    {

        $sessions = $session_repository->findAll();
        return $this->render('admin/admin-sessions.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/{id}/annuler-admin', name: 'app_session_annuler_admin', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function annulerSession(
        Session $session,
        EntityManagerInterface $em,
        Request $request): Response
    {
        if (!$this->isCsrfTokenValid('cancel_session_admin' . $session->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Token invalide.');
        return $this->redirectToRoute('app_admin_sessions');
        }

        $session->setStatus('CANCELLED');
        $session->setCancelledAt(new \DateTimeImmutable('now'));
        $session->setCancelledBy($this->getUser());
        $session->setUpdatedAt(new \DateTimeImmutable('now'));

        $em->flush();

        $this->addFlash('success', 'Ce cours a bien été annulé.');

        return $this->redirectToRoute('app_admin_sessions');

    }







}
