<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ForceHttpsSubscriber implements EventSubscriberInterface
{
    private string $env;

    public function __construct(KernelInterface $kernel)
    {
        $this->env = $kernel->getEnvironment();
    }

    // =========================
    // Méthode appelée à chaque requête HTTP
    // =========================
    public function onKernelRequest(RequestEvent $event)
    {
        // En environnement de développement, on ne force rien
        if ($this->env === 'dev') {
            return;
        }

        // Récupération de la requête courante
        $request = $event->getRequest();
        // Nom de domaine
        $host = $request->getHost();
        // URI complète (chemin + paramètres)
        $uri = $request->getRequestUri();

        // Protocole transmis par le proxy
        $proto = $request->headers->get('X-Forwarded-Proto');

        // Vérifie si la requête est en HTTPS
        $isHttps = $proto === 'https';

        // Domaine cible par défaut
        $targetHost = $host;
        // Indique si une redirection est nécessaire
        $shouldRedirect = false;

        // Si la requête n'est pas en HTTPS, on force la redirection
        if (!$isHttps) {
            $shouldRedirect = true;
        }

        // Si le domaine sans www est utilisé, on redirige vers la version avec www
        if ($host === 'namaste-yoga-studio.fr') {
            $targetHost = 'www.namaste-yoga-studio.fr';
            $shouldRedirect = true;
        }

        // Si une redirection est nécessaire
        if ($shouldRedirect) {
            // Construction de l'URL HTTPS finale
            $redirectUrl = 'https://' . $targetHost . $uri;
            // Redirection permanente
            $event->setResponse(new RedirectResponse($redirectUrl, 301));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }
}
