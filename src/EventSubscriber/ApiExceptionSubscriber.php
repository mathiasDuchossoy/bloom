<?php


namespace BloomAtWork\EventSubscriber;


use BloomAtWork\Exception\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();
        if (!$e instanceof ApiProblemException) {
            return;
        }

        $apiProblem = $e->getApiProblem();
        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
}
