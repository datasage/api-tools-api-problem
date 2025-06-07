<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem;

return [
    'service_manager'       => [
        'factories' => [
            Listener\ApiProblemListener::class             => Factory\ApiProblemListenerFactory::class,
            Listener\RenderErrorListener::class            => Factory\RenderErrorListenerFactory::class,
            Listener\SendApiProblemResponseListener::class => Factory\SendApiProblemResponseListenerFactory::class,
            View\ApiProblemRenderer::class                 => Factory\ApiProblemRendererFactory::class,
            View\ApiProblemStrategy::class                 => Factory\ApiProblemStrategyFactory::class,
        ],
    ],
    'view_manager'          => [
        // Enable this in your application configuration in order to get full
        // exception stack traces in your API-Problem responses.
        'display_exceptions' => false,
    ],
    'api-tools-api-problem' => [
        // Accept types that should allow ApiProblem responses
        // 'accept_filters' => $stringOrArray,

        // Array of controller service names that should enable the ApiProblem render.error listener
        // 'render_error_controllers' => [],
    ],
];
