<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class ServerOverloadException extends LlmIntegrationException
{
    public const NAME = 'ServerOverloadException';
    public const HTTP_CODE = 503;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
