<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class GatewayTimeoutException extends LlmIntegrationException
{
    public const NAME = 'GatewayTimeoutException';
    public const HTTP_CODE = 504;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
