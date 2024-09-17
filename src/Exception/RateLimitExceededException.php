<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class RateLimitExceededException extends LlmIntegrationException
{
    public const NAME = 'RateLimitExceededException';
    public const HTTP_CODE = 429;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
