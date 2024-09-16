<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

#[AiClient('api_together')]
class ApiTogetherClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api.together.xyz/v1/chat/completions';
    }

    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any API Together specific options here
        ];
    }
}
