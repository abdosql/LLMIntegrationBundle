<?php

namespace ContainerBE9xdLy;

return [
    'Saqqal\\LlmIntegrationBundle\\Attribute\\AiClient' => true,
    'Saqqal\\LlmIntegrationBundle\\Attribute\\AiServiceProvider' => true,
    'Saqqal\\LlmIntegrationBundle\\Client\\ApiTogetherClient' => true,
    'Saqqal\\LlmIntegrationBundle\\Client\\OpenAiClient' => true,
    'Saqqal\\LlmIntegrationBundle\\Command\\CreateAiServiceCommand' => true,
    'Saqqal\\LlmIntegrationBundle\\DependencyInjection' => true,
    'Saqqal\\LlmIntegrationBundle\\EventListener\\LlmIntegrationExceptionListener' => true,
    'Saqqal\\LlmIntegrationBundle\\Event\\LlmIntegrationExceptionEvent' => true,
    'Saqqal\\LlmIntegrationBundle\\Exception\\AiClientException' => true,
    'Saqqal\\LlmIntegrationBundle\\Exception\\InvalidConfigurationException' => true,
    'Saqqal\\LlmIntegrationBundle\\Exception\\ModelNotFoundException' => true,
    'Saqqal\\LlmIntegrationBundle\\Exception\\ProviderNotFoundException' => true,
    'Saqqal\\LlmIntegrationBundle\\Interface\\AiClientInterface' => true,
    'Saqqal\\LlmIntegrationBundle\\Interface\\AiServiceInterface' => true,
    'Saqqal\\LlmIntegrationBundle\\LlmIntegrationBundle' => true,
    'Saqqal\\LlmIntegrationBundle\\Response\\AiResponse' => true,
    'Saqqal\\LlmIntegrationBundle\\Services\\ApiTogetherService' => true,
    'Saqqal\\LlmIntegrationBundle\\Services\\OpenAiService' => true,
    'ai_client.api_together' => true,
    'ai_client.main' => true,
    'ai_service.api_together' => true,
    'ai_service.main' => true,
];
