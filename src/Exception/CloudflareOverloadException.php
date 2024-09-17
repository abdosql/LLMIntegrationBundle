<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class CloudflareOverloadException extends LlmIntegrationException
{
    public const NAME = 'CloudflareOverloadException';
    public const HTTP_CODE = 529;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
