<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Interface;

use Saqqal\LlmIntegrationBundle\Exception\AiClientException;
use Saqqal\LlmIntegrationBundle\Exception\ModelNotFoundException;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('llm_integration.ai_service')]
interface AiServiceInterface
{
    /**
     * Generate a response based on the given prompt.
     *
     * @param string $prompt The input prompt
     * @param array $options Additional options for the AI service
     * @return AiResponse The generated response
     * @throws AiClientException
     */
    public function generate(string $prompt, array $options = []): AiResponse;

    /**
     * Get the name of the AI provider.
     *
     * @return string
     */
    public function getProviderName(): string;

    /**
     * Get the current model being used.
     *
     * @return string
     */
    public function getCurrentModel(): string;

    /**
     * Set the model to be used.
     *
     * @param string $model
     * @return void
     * @throws ModelNotFoundException
     */
    public function setModel(string $model): void;
}