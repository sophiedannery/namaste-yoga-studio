<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as ExceptionHttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array 
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    // =========================
    // Méthode appelée automatiquement quand une exception se produit
    // =========================
    public function onKernelException(ExceptionEvent $event): void
    {
        // Récupérer la requête HTTP
        $request = $event->getRequest();
        // Récupérer l'exception levée
        $exception = $event->getThrowable();

        // Si la route ne commence pas par /api, on ne change rien (pages Twig normales)
        if(!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Si c’est une exception HTTP (404, 403, 401, etc.)
        if ($exception instanceof ExceptionHttpExceptionInterface) {

            // Récupérer le code HTTP de l’exception
            $statusCode = $exception->getStatusCode();
            // Construire une réponse JSON standardisée
            $data = [
                'status' => $statusCode,
                'message' => $exception->getMessage()
            ];

            // Remplacer la réponse par une réponse JSON
            $event->setResponse(new JsonResponse($data, $statusCode));
            return;

        } else {
            // Sinon, c’est une erreur serveur non prévue (500)
            $data = [
                'status' => 500,
                'message' => 'Erreur interne du serveur',
            ];

            // Remplacer la réponse par une réponse JSON 500
            $event->setResponse(new JsonResponse($data, 500));
        }
    }
}
