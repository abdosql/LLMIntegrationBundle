<?php

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Client\AbstractAiClient;

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
