<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents an Internal Server Error Exception in the LlmIntegrationBundle.
 */
class InternalServerErrorException extends LlmIntegrationException
{
    public const NAME = 'InternalServerErrorException';
    public const HTTP_CODE = 500;

    /**
     * Constructs the InternalServerErrorException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     *
     * @return void
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
