<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\Listener;

use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\Http\Response as HttpResponse;
use Laminas\Mvc\ResponseSender\HttpResponseSender;
use Laminas\Mvc\ResponseSender\SendResponseEvent;
use Override;

/**
 * Send ApiProblem responses.
 */
class SendApiProblemResponseListener extends HttpResponseSender
{
    protected ?HttpResponse $applicationResponse = null;
    protected bool $displayExceptions            = false;

    public function setApplicationResponse(HttpResponse $response): self
    {
        $this->applicationResponse = $response;

        return $this;
    }

    /**
     * Set the flag determining whether exception stack traces are included.
     */
    public function setDisplayExceptions(bool $flag): self
    {
        $this->displayExceptions = $flag;

        return $this;
    }

    /**
     * Are exception stack traces included in the response?
     */
    public function displayExceptions(): bool
    {
        return $this->displayExceptions;
    }

    /**
     * Send the response content.
     *
     * Sets the composed ApiProblem's flag for including the stack trace in the
     * detail based on the display exceptions flag, and then sends content.
     */
    #[Override]
    public function sendContent(SendResponseEvent $event): HttpResponseSender
    {
        $response = $event->getResponse();
        if (! $response instanceof ApiProblemResponse) {
            return $this;
        }
        $response->getApiProblem()->setDetailIncludesStackTrace($this->displayExceptions());

        return parent::sendContent($event);
    }

    /**
     * Send HTTP response headers.
     *
     * If an application response is composed, and is an HTTP response, merges
     * its headers with the ApiProblemResponse headers prior to sending them.
     */
    #[Override]
    public function sendHeaders(SendResponseEvent $event): SendApiProblemResponseListener
    {
        $response = $event->getResponse();
        if (! $response instanceof ApiProblemResponse) {
            return $this;
        }

        if ($this->applicationResponse instanceof HttpResponse) {
            $this->mergeHeaders($this->applicationResponse, $response);
        }

        return parent::sendHeaders($event);
    }

    /**
     * Send ApiProblem response.
     */
    #[Override]
    public function __invoke(SendResponseEvent $event): self
    {
        $response = $event->getResponse();
        if (! $response instanceof ApiProblemResponse) {
            return $this;
        }

        $this->sendHeaders($event)
             ->sendContent($event);
        $event->stopPropagation(true);

        return $this;
    }

    /**
     * Merge headers set on the application response into the API Problem response.
     */
    protected function mergeHeaders(HttpResponse $applicationResponse, ApiProblemResponse $apiProblemResponse): void
    {
        $apiProblemHeaders = $apiProblemResponse->getHeaders();
        foreach ($applicationResponse->getHeaders() as $header) {
            if ($apiProblemHeaders->has($header->getFieldName())) {
                continue;
            }
            $apiProblemHeaders->addHeader($header);
        }
    }
}
