<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception;

class AiClientException extends LlmIntegrationException
{
    /**
     * Constant representing the name of the exception.
     */
    public const NAME = 'ai-client';

    /**
     * Constructor for AiClientException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     *
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
