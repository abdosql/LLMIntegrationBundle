<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * This class represents an API Together client that extends the AbstractAiClient.
 * It is annotated with the AiClient attribute to specify the client's name.
 */
#[AiClient('api_together')]
class ApiTogetherClient extends AbstractAiClient
{
    /**
     * Returns the API URL for making requests to the API Together service.
     *
     * @return string The API URL.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.together.xyz/v1/chat/completions';
    }

    /**
     * Returns additional request data specific to the API Together service.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The model to be used for the AI response.
     *
     * @return array Additional request data.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any API Together specific options here
        ];
    }
}
