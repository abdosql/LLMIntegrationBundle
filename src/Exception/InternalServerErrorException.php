<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class InternalServerErrorException extends LlmIntegrationException
{
    public const NAME = 'InternalServerErrorException';
    public const HTTP_CODE = 500;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
