<?php

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Client\AbstractAiClient;

/**
 * Prepares the request data for the AI client.
 *
 * This method is responsible for formatting the input prompt and model into a format that can be sent to the AI API.
 *
 * @param string $prompt The input prompt to be sent to the AI model.
 * @param string|null $model The specific AI model to be used for inference. If null, the default model will be used.
 *
 * @return array An associative array containing the formatted request data.
 */
protected function prepareRequestData(string $prompt, ?string $model): array
{
    return [
        "inputs" => $prompt
    ];
}
