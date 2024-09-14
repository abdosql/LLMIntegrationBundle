<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception;

class ProviderNotFoundException extends LlmIntegrationException
{
    /**
     * Exception constant for identifying the exception type.
     */
    public const NAME = "ProviderNotFoundException";

    /**
     * Constructor for ProviderNotFoundException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for exception chaining.
     *
     * @return void
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
