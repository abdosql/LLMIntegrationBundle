<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

#[AiClient('openai')]
class OpenAiClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api.openai.com/v1/chat/completions';
    }

    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any OpenAI specific options here
            'temperature' => 0.7,
            'max_tokens' => 150,
        ];
    }
}
