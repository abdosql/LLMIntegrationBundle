<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

#[AiClient('groq')]
class GroqClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api.groq.com/openai/v1/chat/completions';
    }

    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any Groq specific options here
        ];
    }
}
