<?php
/**
 * SecurityController
 * -----------------------------------------------------------------------------
 * Purpose:
 *   Handle authentication entry points: login and logout.
 *
 * What it does:
 *   - Shows the login page and passes helper data to Twig (last username, error, CSRF token).
 *   - Redirects already-authenticated users away from /login.
 *   - Exposes a /logout route intercepted by the security firewall.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

     /**
     * Display the login page (GET) and show the latest authentication error if any.
     *
     * GET /login
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CsrfTokenManagerInterface $csrfTokenManager, Security $security): Response
    {

        // If an authenticated user hits /login, redirect them to their space.
        if ($user = $security->getUser()) {
            if (in_array('ROLE_TEACHER', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_profile_teacher');
            }
        return $this->redirectToRoute('app_profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Generate a CSRF token for the login form
        $csrfToken = $csrfTokenManager->getToken('authenticate')->getValue();

        // Render the login template
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ]);
    }

    /**
     * Logout endpoint (intercepted by the firewall).
     *
     * GET /logout
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
