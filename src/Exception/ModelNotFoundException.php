<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception;

class ModelNotFoundException extends LlmIntegrationException
{
    /**
     * Exception constant for identifying the type of exception.
     */
    public const NAME = "ModelNotFoundException";
    public const HTTP_CODE = 404;

    /**
     * Constructor for ModelNotFoundException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for exception chaining.
     *
     * @return void
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
