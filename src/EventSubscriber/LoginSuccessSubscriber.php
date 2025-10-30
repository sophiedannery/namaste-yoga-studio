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

    public function onLoginSuccess(LoginSuccessEvent $event): void 
    {
        $token = $event->getAuthenticatedToken(); 
        $roles = $token->getRoleNames();

        if (in_array('ROLE_TEACHER', $roles, true)) {
            $event->setResponse(new RedirectResponse($this->router->generate('app_profile_teacher')));
            return; 
        }

        $event->setResponse(new RedirectResponse($this->router->generate('app_profile')));
    }


}