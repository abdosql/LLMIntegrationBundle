<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents an exception that occurs when authentication fails.
 */
class AuthenticationFailureException extends LlmIntegrationException
{
    public const NAME = 'AuthenticationFailureException';
    public const HTTP_CODE = 401;

    /**
     * Constructor for AuthenticationFailureException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for exception chaining.
     *
     * @throws \Exception If an error occurs during the construction of the exception.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
