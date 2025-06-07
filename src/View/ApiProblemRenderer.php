<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\View;

use Laminas\View\Renderer\JsonRenderer;
use Override;
use RuntimeException;

class ApiProblemRenderer extends JsonRenderer
{
    /**
     * Whether to render exception stack traces in API-Problem payloads.
     */
    protected bool $displayExceptions = false;

    /**
     * Set display_exceptions flag.
     */
    public function setDisplayExceptions(bool $flag): self
    {
        $this->displayExceptions = $flag;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function render($nameOrModel, $values = null): string
    {
        if (! $nameOrModel instanceof ApiProblemModel) {
            return '';
        }

        $apiProblem = $nameOrModel->getApiProblem();
        if (! $apiProblem) {
            throw new RuntimeException('ApiProblem not found');
        }

        if ($this->displayExceptions) {
            $apiProblem->setDetailIncludesStackTrace(true);
        }

        return parent::render($apiProblem->toArray());
    }
}
