<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * Class TavilyClient
 *
 * This class represents a client for interacting with the Tavily AI service.
 * It extends the AbstractAiClient class and implements the required methods for making API requests.
 */
#[AiClient('tavily')]
class TavilyClient extends AbstractAiClient
{
    /**
     * Returns the API URL for making requests to the Tavily service.
     *
     * @return string The API URL.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.tavily.com/search';
    }

    /**
     * Returns the headers to be included in the API request.
     *
     * @return array The request headers.
     */
    protected function getRequestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Prepares the request data for making an API request to the Tavily service.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The AI model to be used for the request.
     *
     * @return array The request data.
     */
    protected function prepareRequestData(string $prompt, ?string $model): array
    {
        return [
            "api_key" =>  $this->apiKey,
            "query" => $prompt
        ];
    }
}
