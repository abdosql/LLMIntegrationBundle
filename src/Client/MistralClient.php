<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class extends AbstractAiClient and is responsible for interacting with the Mistral AI API.
 * It is annotated with the AiClient attribute to specify its associated AI client name.
 */
#[AiClient('mistral')]
class MistralClient extends AbstractAiClient
{
    /**
     * Returns the API URL for the Mistral AI API.
     *
     * @return string The API URL.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.mistral.ai/v1/fim/completions';
    }

    /**
     * Returns additional request data specific to the Mistral AI API.
     *
     * @param string $prompt The prompt to be sent to the AI API.
     * @param string|null $model The model to be used for the AI API request.
     *
     * @return array Additional request data.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any MistralClient specific options here
        ];
    }
}
