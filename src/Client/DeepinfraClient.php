<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class represents a specific AI client for Deepinfra.
 * It extends the AbstractAiClient class and provides methods for interacting with the Deepinfra API.
 *
 * @package Saqqal\LlmIntegrationBundle\Client
 * @author YourName <your-email>
 */
#[AiClient('deepinfra')]
class DeepinfraClient extends AbstractAiClient
{
    /**
     * Returns the API URL for the Deepinfra service.
     *
     * @return string The API URL for making requests to Deepinfra.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.deepinfra.com/v1/openai/chat/completions';
    }

    /**
     * Returns additional request data specific to the Deepinfra client.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The AI model to use for the request.
     * @return array Additional request data specific to Deepinfra.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any DeepinfraClient specific options here
        ];
    }
}
