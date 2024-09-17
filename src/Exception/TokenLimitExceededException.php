<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class TokenLimitExceededException extends LlmIntegrationException
{
    public const NAME = 'TokenLimitExceededException';
    public const HTTP_CODE = 403;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
