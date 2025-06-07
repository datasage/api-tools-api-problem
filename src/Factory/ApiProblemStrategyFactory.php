<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\ApiProblem\View\ApiProblemRenderer;
use Laminas\ApiTools\ApiProblem\View\ApiProblemStrategy;

class ApiProblemStrategyFactory
{
    public function __invoke(ContainerInterface $container): ApiProblemStrategy
    {
        return new ApiProblemStrategy($container->get(ApiProblemRenderer::class));
    }
}
