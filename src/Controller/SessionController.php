<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionForm;
use App\Service\ReservationService;
use App\Service\SessionService;
use App\Stats\StatsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class SessionController extends AbstractController
{
    // =========================
    // Page planning (liste des cours)
    // =========================
    #[Route('/planning', name: 'app_session_planning')]
    public function planningTest(): Response
    {
        return $this->render('session/session-planning.html.twig', []);
    }

    // =========================
    // Page détails d’un cours
    // =========================
    #[Route('/session-details/{id}', name: 'app_session_details', requirements: ['id' => '\d+'])]
    public function details(
        Session $session,
        Request $request,
        ReservationService $reservationService
        ): Response
    {
        // Récupère l’URL de la page précédente (pour bouton "retour")
        $referer = $request->headers->get('referer');

        // Calcul du nombre de places restantes
        $remaining = $reservationService->getRemainingPlaces($session);

        return $this->render('session/session-details.html.twig', [
            'session' => $session,
            'previousUrl' => $referer ?? '/',
            'remaining' => $remaining,
        ]);
    }


    // =========================
    // Création d’une session (professeur)
    // =========================
    #[Route('/teacher/session/ajout', name: 'app_session_new')]
    #[IsGranted('ROLE_TEACHER')] // Accès réservé aux professeurs
    public function newSession(
        Request $request,
        SessionService $sessionService,
        StatsCounter $counter
        ): Response
    {
        // Récupérer le professeur connecté
        /** @var \App\Entity\User $user */
            $teacher = $this->getUser();
        
        // Créer une nouvelle session
        $session = new Session();

        // Préparer la session avec le service Session
        $sessionService->prepareNewSession($session, $teacher);

        // Créer le formulaire lié à la session
        $form = $this->createForm(SessionForm::class, $session);
        // Traitement de la requête (POST)
        $form->handleRequest($request);

        // Si formulaire soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Appel au service session
            $sessionService->create($session);
            // Incrémenter le compteur
            $counter->incCreated(1);
            // Message de succès
            $this->addFlash('success', 'Cours ajouté avec succès !');
            // Redirection vers le planning professeur
            return $this->redirectToRoute('app_profile_teacher_planning');
        }

        return $this->render('session/session-new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
