<?php

/**
 * RegistrationController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Handle public user registration.
 *
 * What it does:
 *   - Shows the registration form (GET /register).
 *   - Handles form submission (POST /register).
 *   - Hashes password, normalizes email, assigns ROLE_USER, activates the account.
 *   - Logs validation errors.
 *   - Automatically logs the user in after a successful registration.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    /**
     * Display and process the registration form.
     *
     * GET|POST /register
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {

        // If a logged-in user hits /register, send them to their profile.
        if ($security->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $user = new User();

        // Create and bind the form to the new User entity.
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // On valid submission: finalize account creation.
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
        
            $plainPassword = $form->get('plainPassword')->getData();

             // Hash the password BEFORE persisting
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            // Assign the default role.
            $user->setRoles(['ROLE_USER']);
            // Normalize email to lowercase
            $user->setEmail(mb_strtolower($user->getEmail() ?? ''));
            // Mark user as active
            $user->setIsActive(true);

             // Persist to the database.
            $entityManager->persist($user);
            $entityManager->flush();

            // Auto-login the freshly registered user.
            $security->login($user, 'form_login', 'main');
            // Redirect to the private space after successful registration.
            return $this->redirectToRoute('app_profile');
        }

        // If the form was submitted but is invalid, collect errors and log them.
        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors(true, false) as $error) {
                if ($error instanceof \Symfony\Component\Form\FormError) {
                    $errors[] = $error->getMessage();
                }
            }

            $logger->error('Inscription invalide', ['errors' => $errors]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
