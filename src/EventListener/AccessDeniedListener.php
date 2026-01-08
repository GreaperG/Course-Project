<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class AccessDeniedListener
{
    public function __construct(
        private RequestStack $requestStack,
        private RouterInterface $router,
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if(!$exception instanceof AccessDeniedException && !$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $session = $this->requestStack->getSession();

        $session->getFlashBag()->add('error', 'You do not have access to this page.');

        $referer = $event->getRequest()->headers->get('referer');

        if($referer) {
            $response = new RedirectResponse($referer);
        } else {

            $url = $this->router->generate('app_inventory_index');
            $response = new RedirectResponse($url);
        }

        $event->setResponse($response);
    }
}



?>

