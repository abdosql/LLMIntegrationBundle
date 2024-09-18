<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * Represents a Gateway Timeout Exception in the LlmIntegrationBundle.
 * This exception is thrown when a gateway times out while attempting to connect to a service.
 */
class GatewayTimeoutException extends LlmIntegrationException
{
    public const NAME = 'GatewayTimeoutException';
    public const HTTP_CODE = 504;

    /**
     * Constructor for the GatewayTimeoutException.
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
