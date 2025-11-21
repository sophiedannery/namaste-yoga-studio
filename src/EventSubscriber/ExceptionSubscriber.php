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



    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $exception = $event->getThrowable();

        if(!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        if ($exception instanceof ExceptionHttpExceptionInterface) {

            $statusCode = $exception->getStatusCode();

            $data = [
                'status' => $statusCode,
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data, $statusCode));
            return;

        } else {

            $data = [
                'status' => 500,
                'message' => 'Erreur interne du serveur',
            ];

            $event->setResponse(new JsonResponse($data, 500));
        }
    }

    
}
