<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents a custom exception for Cloudflare timeout errors.
 */
class CloudflareTimeoutException extends LlmIntegrationException
{
    public const NAME = 'CloudflareTimeoutException';
    public const HTTP_CODE = 524;

    /**
     * Constructs the CloudflareTimeoutException with a custom message and optional previous exception.
     *
     * @param string         $message    The custom error message.
     * @param \Throwable|null $previous  The previous exception that caused this exception.
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
