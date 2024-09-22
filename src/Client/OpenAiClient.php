<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class represents an OpenAI client that extends the AbstractAiClient.
 * It is annotated with the AiClient attribute to specify the AI client type.
 */
#[AiClient('openai')]
class OpenAiClient extends AbstractAiClient
{
    /**
     * Returns the API URL for the OpenAI client.
     *
     * @return string The API URL for the OpenAI client.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Returns additional request data specific to the OpenAI client.
     *
     * @param string $prompt The prompt to be sent to the AI client.
     * @param string|null $model The model to be used for the AI client.
     *
     * @return array An array containing additional request data specific to the OpenAI client.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any OpenAI specific options here
            'temperature' => 0.7,
            'max_tokens' => 150,
        ];
    }
}
