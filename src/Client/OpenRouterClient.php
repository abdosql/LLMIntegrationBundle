<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class represents an Open Router client that extends the AbstractAiClient.
 * It is annotated with the AiClient attribute to specify the client's name.
 */
#[AiClient('openRouter')]
class OpenRouterClient extends AbstractAiClient
{
    /**
     * Returns the API URL for making requests to the Open Router service.
     *
     * @return string The API URL.
     */
    protected function getApiUrl(): string
    {
        return 'https://openrouter.ai/api/v1/chat/completions';
    }

    /**
     * Returns additional request data specific to the Open Router service.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The model to be used for the AI response.
     *
     * @return array Additional request data.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any Open Router specific options here
        ];
    }
}
