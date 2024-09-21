<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Exception\Handler;

use Saqqal\LlmIntegrationBundle\Exception\DefaultLlmIntegrationException;
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
     * @throws \Exception
     */
    public function handle(\Throwable $exception, ResponseInterface $response): LlmIntegrationException
    {
        if ($exception instanceof LlmIntegrationException) {
            return $exception;
        }
        $responseMessage = json_decode($response->getContent(false))->error->message ?? $exception->getMessage();
        $statusCode = $response->getStatusCode();

        return match ($statusCode) {
            InvalidRequestException::HTTP_CODE => new InvalidRequestException($responseMessage, $exception),
            AuthenticationFailureException::HTTP_CODE => new AuthenticationFailureException($responseMessage, $exception),
            TokenLimitExceededException::HTTP_CODE => new TokenLimitExceededException($responseMessage, $exception),
            ModelNotFoundException::HTTP_CODE => new ModelNotFoundException($responseMessage, $exception),
            RateLimitExceededException::HTTP_CODE => new RateLimitExceededException($responseMessage, $exception),
            InternalServerErrorException::HTTP_CODE => new InternalServerErrorException($responseMessage, $exception),
            ServerOverloadException::HTTP_CODE => new ServerOverloadException($responseMessage, $exception),
            GatewayTimeoutException::HTTP_CODE => new GatewayTimeoutException($responseMessage, $exception),
            CloudflareTimeoutException::HTTP_CODE => new CloudflareTimeoutException($responseMessage, $exception),
            CloudflareOverloadException::HTTP_CODE => new CloudflareOverloadException($responseMessage, $exception),
            default => new DefaultLlmIntegrationException($responseMessage, $exception),
        };
    }
}
