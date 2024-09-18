<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents a custom exception for rate limit exceeded errors in the LlmIntegrationBundle.
 * It extends the LlmIntegrationException class and provides specific HTTP code and name for this exception.
 */
class RateLimitExceededException extends LlmIntegrationException
{
    /**
     * The name of the exception.
     */
    public const NAME = 'RateLimitExceededException';

    /**
     * The HTTP code associated with this exception.
     */
    public const HTTP_CODE = 429;

    /**
     * Constructs a new RateLimitExceededException.
     *
     * @param string         $message  The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
