<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
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

#[AiClient('api_together')]
class ApiTogetherClient implements AiClientInterface
{
    private const API_URL = 'https://api.together.xyz/v1/chat/completions';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private EventDispatcherInterface     $eventDispatcher,
        private string                       $apiKey,
        private ?string                      $defaultModel
    ) {
    }

    /**
     * Sends a prompt to the AI API and returns the response.
     *
     * @param string $prompt The prompt to send to the AI API.
     * @param string|null $model The model to use for the AI API. If null, the default model will be used.
     *
     * @return AiResponse The response from the AI API.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws AiClientException
     */
    public function sendPrompt(string $prompt, ?string $model = null): AiResponse
    {
        $model = $model ?? $this->defaultModel;
        try {
            // Send the prompt to the AI API
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'stream' => false,
                ],
            ]);
            // Check the response status code
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new AiClientException('API request failed with status code: ' . $statusCode);
            }

            // Parse the response content
            $content = json_decode($response->getContent(), true);

            // Check the response structure
            if (!isset($content['choices'][0]['message']['content'])) {
                throw new AiClientException('Unexpected response structure from API');
            }

            // Create and return the AiResponse object
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
            dd($e);
            // Handle exceptions and dispatch an event
            $exception = new AiClientException('Unexpected response structure from API', $e);
            $event = new LlmIntegrationExceptionEvent($exception);
            $this->eventDispatcher->dispatch($event, AiClientException::NAME);
            throw $exception;
        }
    }
}
