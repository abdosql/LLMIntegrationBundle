<?php

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Event\Dispatcher\ExceptionEventDispatcher;
use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Saqqal\LlmIntegrationBundle\Exception\AiClientException;
use Saqqal\LlmIntegrationBundle\Exception\Handler\ExceptionHandler;
use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;
use Saqqal\LlmIntegrationBundle\Interface\AiClientInterface;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Saqqal\LlmIntegrationBundle\Response\DynamicAiResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Abstract class for AI clients implementing common functionality.
 */
abstract class AbstractAiClient implements AiClientInterface
{
    /**
     * Constructor for AbstractAiClient.
     *
     * @param HttpClientInterface $httpClient The HTTP client for making API requests.
     * @param ExceptionEventDispatcher $exceptionEventDispatcher
     * @param ExceptionHandler $exceptionHandler
     * @param string $apiKey The API key for authentication.
     * @param string|null $defaultModel The default AI model to use.
     */
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
        protected ExceptionEventDispatcher $exceptionEventDispatcher,
        protected ExceptionHandler $exceptionHandler,
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
     * @param bool|null $dynamicResponse
     * @return DynamicAiResponse|AiResponse The response from the AI service.
     * @throws ClientExceptionInterface
     * @throws LlmIntegrationException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function sendPrompt(string $prompt, ?string $model = null, bool|null $dynamicResponse = false): DynamicAiResponse|AiResponse
    {
        $model = $model ?? $this->defaultModel;
        try {
            $requestData = $this->prepareRequestData($prompt, $model);

            $response = $this->httpClient->request('POST', $this->getApiUrl(), [
                'headers' => $this->getRequestHeaders(),
                'json' => $requestData,
            ]);

            return $this->handleResponse($response, $dynamicResponse);
        } catch (\Throwable $e) {
            $exception = $this->exceptionHandler->handle($e, $response ?? null);
            $this->dispatchException($exception);
            throw $exception;
        }
    }

    /**
     * Handles the response from the AI service and returns the appropriate response object.
     *
     * @param ResponseInterface $response The HTTP response from the AI service.
     * @param bool $dynamicResponse A flag indicating whether a dynamic response object should be returned.
     * @return DynamicAiResponse|AiResponse The response object from the AI service.
     * @throws AiClientException If the response structure is unexpected.
     * @throws ClientExceptionInterface If an error occurs during the HTTP request.
     * @throws LlmIntegrationException If an error occurs during the handling of the response.
     * @throws RedirectionExceptionInterface If a redirection error occurs during the HTTP request.
     * @throws ServerExceptionInterface If a server error occurs during the HTTP request.
     * @throws TransportExceptionInterface If a transport error occurs during the HTTP request.
     */
    protected function handleResponse(ResponseInterface $response, bool $dynamicResponse): DynamicAiResponse|AiResponse
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw $this->exceptionHandler->handle(
                new \Exception('API request failed with status code: ' . $statusCode),
                $response
            );
        }

        $content = json_decode($response->getContent(), true);

        if (!isset($content['choices'][0]['message']['content'])) {
            throw new AiClientException('Unexpected response structure from API');
        }

        return $dynamicResponse
            ? $this->createDynamicAiResponse($content)
            : $this->createStandardAiResponse($content);
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

    protected function getRequestHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
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

    /**
     * Dispatches an LlmIntegrationException event.
     *
     * This method creates a new LlmIntegrationExceptionEvent with the given exception and dispatches it
     * using the exceptionEventDispatcher.
     *
     * @param LlmIntegrationException $exception The exception to be dispatched.
     * @return void
     */
    protected function dispatchException(LlmIntegrationException $exception): void
    {
        $event = new LlmIntegrationExceptionEvent($exception);
        $this->exceptionEventDispatcher->dispatchException($event);
    }
    /**
     * Creates a DynamicAiResponse object from the given API response data.
     *
     * This function takes an associative array representing the API response and creates a new DynamicAiResponse object.
     * It then populates the object with the response data using the populate method of the DynamicAiResponse class.
     *
     * @param array $response The associative array representing the API response.
     * @return DynamicAiResponse The newly created and populated DynamicAiResponse object.
     */
    protected function createDynamicAiResponse(array $response): DynamicAiResponse
    {
        $aiResponse = new DynamicAiResponse();
        $aiResponse->populate($response);

        return $aiResponse;
    }

    /**
     * Creates a StandardAiResponse object from the given API response data.
     *
     * This function takes an associative array representing the API response and creates a new AiResponse object.
     * It then populates the object with the response data. The function specifically handles the 'content', 'id', 'model',
     * and 'usage' fields from the API response.
     *
     * @param array $content The associative array representing the API response.
     * @return AiResponse The newly created and populated AiResponse object.
     *
     * @throws AiClientException If the 'choices', 'message', or 'content' fields are not present in the response.
     */
    private function createStandardAiResponse(array $content): AiResponse
    {
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
    }
}
