<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents a custom exception for server overload errors.
 * It extends the LlmIntegrationException class and provides specific HTTP code and name for this exception.
 */
class ServerOverloadException extends LlmIntegrationException
{
    /**
     * Exception name.
     */
    public const NAME = 'ServerOverloadException';

    /**
     * HTTP status code for this exception.
     */
    public const HTTP_CODE = 503;

    /**
     * Constructor for ServerOverloadException.
     *
     * @param string         $message  The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     *
     * @throws \Exception If the provided message is empty.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
