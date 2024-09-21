<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 *
 *  Interface for AI clients that interact with LLM (Large Language Model) APIs.
 */

namespace Saqqal\LlmIntegrationBundle\Interface;

use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Saqqal\LlmIntegrationBundle\Response\DynamicAiResponse;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('llm_integration.ai_client')]
interface AiClientInterface
{
    /**
     * Sends a prompt to the LLM API and returns the response.
     *
     * @param string $prompt The prompt to be sent to the LLM.
     * @param string|null $model The model to be used for the LLM. If null, the default model will be used.
     * @param bool|null $dynamicResponse Indicates whether the response should be dynamic or not. If null, the default behavior will be used.
     *
     * @return AiResponse|DynamicAiResponse The response from the LLM API.
     */
    public function sendPrompt(string $prompt, ?string $model = null, ?bool $dynamicResponse = null): mixed;
}
