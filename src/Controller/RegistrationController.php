<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    // =========================
    // Inscription d’un nouvel utilisateur
    // =========================
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        Security $security,
        LoggerInterface $logger,
        RegistrationService $registrationService
    ): Response {

        // Si un utilisateur est déjà connecté, on le redirige vers son espace
        if ($security->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        // Création d’un nouvel utilisateur
        $user = new User();

        // Création du formulaire d’inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Traitement de la requête (POST)
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du mot de passe en clair depuis le formulaire
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            // Appel du service d’inscription
            $registrationService->register($user, $plainPassword);
            // Connexion automatique de l’utilisateur après inscription
            $security->login($user, 'form_login', 'main');
            // Redirection vers l’espace personnel
            return $this->redirectToRoute('app_profile');
        }

        // Si le formulaire est soumis mais invalide
        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            // Récupération des messages d’erreur du formulaire
            foreach ($form->getErrors(true, false) as $error) {
                if ($error instanceof \Symfony\Component\Form\FormError) {
                    $errors[] = $error->getMessage();
                }
            }
            // Log des erreurs 
            $logger->error('Inscription invalide', ['errors' => $errors]);
        }

        // Affichage du formulaire d’inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
