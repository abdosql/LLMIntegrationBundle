<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * Custom exception class for handling token limit exceeded exceptions in the LLM Integration bundle.
 *
 * This exception is thrown when the number of tokens used for LLM requests exceeds the allowed limit.
 */
class TokenLimitExceededException extends LlmIntegrationException
{
    public const NAME = 'TokenLimitExceededException';
    public const HTTP_CODE = 403;

    /**
     * Constructor for the TokenLimitExceededException class.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for exception chaining.
     *
     * @throws \Exception If an error occurs during the exception construction.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
