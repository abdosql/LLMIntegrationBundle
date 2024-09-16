<?php

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Saqqal\LlmIntegrationBundle\Exception\AiClientException;
use Saqqal\LlmIntegrationBundle\Interface\AiClientInterface;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractAiClient implements AiClientInterface
{
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
        protected EventDispatcherInterface $eventDispatcher,
        protected string $apiKey,
        protected ?string $defaultModel
    ) {
    }

    abstract protected function getApiUrl(): string;

    /**
     * @throws AiClientException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function sendPrompt(string $prompt, ?string $model = null): AiResponse
    {
        $model = $model ?? $this->defaultModel;
        try {
            $requestData = $this->prepareRequestData($prompt, $model);

            $response = $this->httpClient->request('POST', $this->getApiUrl(), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new AiClientException('API request failed with status code: ' . $statusCode);
            }

            $content = json_decode($response->getContent(), true);

            if (!isset($content['choices'][0]['message']['content'])) {
                throw new AiClientException('Unexpected response structure from API');
            }

            return new AiResponse(
                status: 'success',
                data: [
                    'content' => $content['choices'][0]['message']['content'],
                ],
                metadata: [
                    'id' => $content['id'] ?? null,
                    'model' => $content['model'] ?? null,
                    'usage' => $content['usage'] ?? null,
                ]
            );
        } catch (\Exception $e) {
            $exception = new AiClientException('Error processing API request', $e);
            $event = new LlmIntegrationExceptionEvent($exception);
            $this->eventDispatcher->dispatch($event, AiClientException::NAME);
            throw $exception;
        }
    }

    protected function prepareRequestData(string $prompt, ?string $model): array
    {
        $defaultData = [
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'stream' => false,
        ];

        return array_merge($defaultData, $this->getAdditionalRequestData($prompt, $model));
    }

    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [];
    }

    protected function getDefaultRequestData(string $prompt, ?string $model): array
    {
        return [
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'stream' => false,
        ];
    }
}
