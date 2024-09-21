<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Client\AbstractAiClient;

/**
 * This class represents an Arliai AI client, extending the AbstractAiClient.
 * It is annotated with the AiClient attribute to identify it as an AI client.
 */
#[AiClient('arliai')]
class ArliaiClient extends AbstractAiClient
{
    /**
     * Returns the API URL for the Arliai AI client.
     *
     * @return string The API URL for making requests to the Arliai AI service.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.arliai.com/v1/chat/completions';
    }

    /**
     * Returns additional request data specific to the Arliai AI client.
     *
     * @param string $prompt The user's input prompt.
     * @param string|null $model The AI model to use for the request.
     *
     * @return array Additional request data specific to the Arliai AI client.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any ArliaiClient specific options here
        ];
    }
}
