<?php

namespace Saqqal\LlmIntegrationBundle\Exception;

class AuthenticationFailureException extends LlmIntegrationException
{
    public const NAME = 'AuthenticationFailureException';
    public const HTTP_CODE = 401;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, self::HTTP_CODE, $previous);
    }
}
