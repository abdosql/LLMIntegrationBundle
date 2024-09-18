<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * Represents an exception thrown when an invalid request is made to the LlmIntegrationBundle.
 */
class InvalidRequestException extends LlmIntegrationException
{
    /**
     * The name of the exception.
     */
    public const NAME = 'InvalidRequestException';

    /**
     * The HTTP status code associated with this exception.
     */
    public const HTTP_CODE = 400;

    /**
     * Constructs the InvalidRequestException.
     *
     * @param string         $message  The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
