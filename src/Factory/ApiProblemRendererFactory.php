<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\ApiProblem\View\ApiProblemRenderer;

class ApiProblemRendererFactory
{
    public function __invoke(ContainerInterface $container): ApiProblemRenderer
    {
        $config            = $container->get('config');
        $displayExceptions = isset($config['view_manager']['display_exceptions']) && $config['view_manager']['display_exceptions'];

        $renderer = new ApiProblemRenderer();
        $renderer->setDisplayExceptions($displayExceptions);

        return $renderer;
    }
}
