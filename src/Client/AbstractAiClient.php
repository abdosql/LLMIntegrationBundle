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

/**
 * Abstract class for AI clients implementing common functionality.
 */
abstract class AbstractAiClient implements AiClientInterface
{
    /**
     * Constructor for AbstractAiClient.
     *
     * @param HttpClientInterface $httpClient The HTTP client for making API requests.
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher for handling exceptions.
     * @param string $apiKey The API key for authentication.
     * @param string|null $defaultModel The default AI model to use.
     */
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
        protected EventDispatcherInterface $eventDispatcher,
        protected string $apiKey,
        protected ?string $defaultModel
    ) {
    }

    /**
     * Abstract method to get the API URL for the specific AI service.
     *
     * @return string The API URL.
     */
    abstract protected function getApiUrl(): string;

    /**
     * Sends a prompt to the AI service and returns the response.
     *
     * @param string $prompt The prompt to send to the AI.
     * @param string|null $model The specific model to use (optional).
     * @return AiResponse The response from the AI service.
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

    /**
     * Prepares the request data for the AI service.
     *
     * @param string $prompt The prompt to send.
     * @param string|null $model The model to use.
     * @return array The prepared request data.
     */
    protected function prepareRequestData(string $prompt, ?string $model): array
    {
        $defaultData = [
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'stream' => false,
        ];

        return array_merge($defaultData, $this->getAdditionalRequestData($prompt, $model));
    }

    /**
     * Gets additional request data specific to the AI service.
     * Can be overridden by child classes to add service-specific data.
     *
     * @param string $prompt The prompt to send.
     * @param string|null $model The model to use.
     * @return array Additional request data.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [];
    }

    /**
     * Gets the default request data structure.
     *
     * @param string $prompt The prompt to send.
     * @param string|null $model The model to use.
     * @return array The default request data.
     */
    protected function getDefaultRequestData(string $prompt, ?string $model): array
    {
        return [
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'stream' => false,
        ];
    }
}
