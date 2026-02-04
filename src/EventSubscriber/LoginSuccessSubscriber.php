<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final class LoginSuccessSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router) {}

    public static function getSubscribedEvents(): array 
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    // =========================
    // Méthode appelée après une authentification réussie
    // =========================
    public function onLoginSuccess(LoginSuccessEvent $event): void 
    {
        // Récupère le token de sécurité de l’utilisateur authentifié
        $token = $event->getAuthenticatedToken();
        // Récupère la liste des rôles de l’utilisateur
        $roles = $token->getRoleNames();

        // Si l'utilisateur est professeur, redirection vers l’espace professeur
        if (in_array('ROLE_TEACHER', $roles, true)) {
            $event->setResponse(new RedirectResponse($this->router->generate('app_profile_teacher')));
            return;
        }

         // Si l'utilisateur est administrateur, redirection vers le tableau de bord admin
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $event->setResponse(new RedirectResponse($this->router->generate('app_admin')));
            return;
        }

        // Par défaut (élève), redirection vers l’espace élève
        $event->setResponse(
            new RedirectResponse(
                $this->router->generate('app_profile'))
            );
    }
}