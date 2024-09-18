<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * @method getMessage()
 */
/**
 * Abstract class representing a custom exception for LlmIntegrationBundle.
 */
abstract class LlmIntegrationException extends \Exception
{
    public const NAME = 'LlmIntegrationException';

    /**
     * Constructor for LlmIntegrationException.
     *
     * @param string $message The exception message.
     * @param int $code The exception code. Default is 0.
     * @param \Throwable|null $previous The previous throwable used for exception chaining. Default is null.
     */
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
