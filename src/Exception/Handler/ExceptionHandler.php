<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception\Handler;

use Saqqal\LlmIntegrationBundle\Exception\InvalidRequestException;
use Saqqal\LlmIntegrationBundle\Exception\AuthenticationFailureException;
use Saqqal\LlmIntegrationBundle\Exception\TokenLimitExceededException;
use Saqqal\LlmIntegrationBundle\Exception\ModelNotFoundException;
use Saqqal\LlmIntegrationBundle\Exception\RateLimitExceededException;
use Saqqal\LlmIntegrationBundle\Exception\InternalServerErrorException;
use Saqqal\LlmIntegrationBundle\Exception\ServerOverloadException;
use Saqqal\LlmIntegrationBundle\Exception\GatewayTimeoutException;
use Saqqal\LlmIntegrationBundle\Exception\CloudflareTimeoutException;
use Saqqal\LlmIntegrationBundle\Exception\CloudflareOverloadException;
use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExceptionHandler
{
    /**
     * @param \Throwable $exception
     * @param ResponseInterface $response
     * @return LlmIntegrationException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(\Throwable $exception, ResponseInterface $response): LlmIntegrationException
    {
        if ($exception instanceof LlmIntegrationException) {
            return $exception;
        }

        $responseMessage = json_decode($response->getContent(false))->error->message;
        $statusCode = $response->getStatusCode();

        return match ($statusCode) {
            400 => new InvalidRequestException($responseMessage, $exception),
            401 => new AuthenticationFailureException($responseMessage, $exception),
            403 => new TokenLimitExceededException($responseMessage, $exception),
            404 => new ModelNotFoundException($responseMessage, $exception),
            429 => new RateLimitExceededException($responseMessage, $exception),
            500 => new InternalServerErrorException($responseMessage, $exception),
            503 => new ServerOverloadException($responseMessage, $exception),
            504 => new GatewayTimeoutException($responseMessage, $exception),
            524 => new CloudflareTimeoutException($responseMessage, $exception),
            529 => new CloudflareOverloadException($responseMessage, $exception),
        };
    }
}
