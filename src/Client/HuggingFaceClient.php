<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

#[AiClient('huggingface')]
class HuggingFaceClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api-inference.huggingface.co/models/'.$this->defaultModel;
    }

    protected function prepareRequestData(string $prompt, ?string $model): array
    {
        return [
            "inputs" => $prompt
        ];
    }
}
