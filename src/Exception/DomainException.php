<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\Exception;

use Override;

class DomainException extends \DomainException implements
    ExceptionInterface,
    ProblemExceptionInterface
{
    /** @var string */
    protected $type;

    /** @var array */
    protected $details = [];

    /** @var string */
    protected $title;

    /**
     * @return self
     */
    public function setAdditionalDetails(array $details)
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @param string $uri
     * @return self
     */
    public function setType($uri)
    {
        $this->type = (string) $uri;
        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * @return array
     */
    #[Override]
    public function getAdditionalDetails()
    {
        return $this->details;
    }

    /**
     * @return string
     */
    #[Override]
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    #[Override]
    public function getTitle()
    {
        return $this->title;
    }
}
