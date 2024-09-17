<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class CloudflareTimeoutException extends LlmIntegrationException
{
    public const NAME = 'CloudflareTimeoutException';
    public const HTTP_CODE = 524;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
