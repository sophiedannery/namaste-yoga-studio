<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // =========================
    // Page de connexion
    // =========================
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        CsrfTokenManagerInterface $csrfTokenManager,
        Security $security
        ): Response
    {
        // Si un utilisateur est déjà connecté
        if ($user = $security->getUser()) {

            // Si c’est admin, redirection vers l’espace admin
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_admin');
            }

            // Si c’est un professeur, redirection vers l’espace professeur
            if (in_array('ROLE_TEACHER', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_profile_teacher');
            }

        // Sinon, redirection vers l’espace élève
        return $this->redirectToRoute('app_profile');
        }

        // Récupérer la dernière erreur d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupérer le dernier identifiant saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // Générer le token CSRF pour sécuriser le formulaire de login
        $csrfToken = $csrfTokenManager->getToken('authenticate')->getValue();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ]);
    }

    // =========================
    // Déconnexion
    // =========================
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
