<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class InvalidRequestException extends LlmIntegrationException
{
    public const NAME = 'InvalidRequestException';
    public const HTTP_CODE = 400;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
