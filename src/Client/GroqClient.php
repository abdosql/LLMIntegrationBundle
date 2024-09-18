<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

/**
 * Represents a Groq client for integrating with the LLM (Language Model) API.
 * This class extends AbstractAiClient and is annotated with AiClient attribute to specify the LLM model used.
 */
#[AiClient('groq')]
class GroqClient extends AbstractAiClient
{
    /**
     * Returns the API URL for Groq LLM model.
     *
     * @return string The API URL for Groq LLM model.
     */
    protected function getApiUrl(): string
    {
        return 'https://api.groq.com/openai/v1/chat/completions';
    }

    /**
     * Returns additional request data specific to Groq LLM model.
     *
     * @param string $prompt The prompt to be sent to the LLM model.
     * @param string|null $model The LLM model to be used. If null, the default model will be used.
     *
     * @return array Additional request data specific to Groq LLM model.
     */
    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add any Groq specific options here
        ];
    }
}
