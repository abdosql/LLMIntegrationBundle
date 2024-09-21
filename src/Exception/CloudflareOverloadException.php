<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

/**
 * This class represents a custom exception for handling Cloudflare overload errors.
 */
class CloudflareOverloadException extends LlmIntegrationException
{
    public const NAME = 'CloudflareOverloadException';
    public const HTTP_CODE = 529;

    /**
     * Constructs a new CloudflareOverloadException.
     *
     * @param string $message The exception message.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     *
     */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
