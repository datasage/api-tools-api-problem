<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem;

use Laminas\ApiTools\ApiProblem\Listener\SendApiProblemResponseListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\ResponseSender\SendResponseEvent;

class Module
{
    /**
     * Retrieve module configuration
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listener for bootstrap event.
     *
     * Attaches a render event.
     */
    public function onBootstrap(MvcEvent $e): void
    {
        $app            = $e->getApplication();
        $serviceManager = $app->getServiceManager();
        $eventManager   = $app->getEventManager();

        $serviceManager->get(Listener\ApiProblemListener::class)->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], 100);

        $sendResponseListener = $serviceManager->get('SendResponseListener');
        $sendResponseListener->getEventManager()->attach(
            SendResponseEvent::EVENT_SEND_RESPONSE,
            $serviceManager->get(SendApiProblemResponseListener::class),
            -500
        );
    }

    /**
     * Listener for the render event.
     *
     * Attaches a rendering/response strategy to the View.
     */
    public function onRender(MvcEvent $e): void
    {
        $app      = $e->getApplication();
        $services = $app->getServiceManager();

        if ($services->has('View')) {
            $view   = $services->get('View');
            $events = $view->getEventManager();

            // register at high priority, to "beat" normal json strategy registered
            // via view manager, as well as HAL strategy.
            $services->get(View\ApiProblemStrategy::class)->attach($events, 400);
        }
    }
}
