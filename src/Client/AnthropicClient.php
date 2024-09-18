<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class represents an Anthropic client that extends the AbstractAiClient.
 * It is annotated with the AiClient attribute to specify the client's name.
 */
#[AiClient('anthropic')]
class AnthropicClient extends AbstractAiClient
{
    /**
     * Returns the API URL for making requests to the Anthropic service.
     *
     * @return string The API URL.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.anthropic.com/v1/messages';
    }

    /**
     * Returns additional request data specific to the Anthropic service.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The model to be used for the AI response.
     *
     * @return array Additional request data.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any Anthropic specific options here
        ];
    }
    protected function getRequestHeaders(): array
    {
        return [
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ];
    }
}
