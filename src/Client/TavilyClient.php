<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

#[AiClient('tavily')]
class TavilyClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api.tavily.com/search';
    }
    protected function getRequestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function prepareRequestData(string $prompt, ?string $model): array
    {
        return [
            "api_key" =>  $this->apiKey,
            "query" => $prompt
        ];
    }
}
