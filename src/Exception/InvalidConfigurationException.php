<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception;

class InvalidConfigurationException extends LlmIntegrationException
{
    /**
     * A constant representing the name of the exception.
     */
    public const NAME = "InvalidConfigurationException";

    /**
     * Constructor for the InvalidConfigurationException class.
     *
     * @param string $message The error message.
     * @param \Throwable|null $previous The previous throwable causing this exception.
     *
     * @return void
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
