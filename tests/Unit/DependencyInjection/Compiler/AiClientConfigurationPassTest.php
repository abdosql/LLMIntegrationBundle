<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiClientConfigurationPass;
use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AiClientConfigurationPassTest extends TestCase
{
    private ContainerBuilder $container;
    private AiClientConfigurationPass $pass;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->pass = new AiClientConfigurationPass();
    }

    public function testProcess(): void
    {
        $this->container->setParameter('llm_integration.provider', 'api_together');
        $this->container->setParameter('llm_integration.api_key', 'test_api_key');
        $this->container->setParameter('llm_integration.model', 'test_model');

        // Create a mock client class with the AiClient attribute
        $mockClientClass = new class ('dummy_key', 'dummy_model') {
            #[AiClient('api_together')]
            public function __construct(public string $apiKey, public string $defaultModel)
            {
            }
        };

        $clientDefinition = new Definition(get_class($mockClientClass));
        $clientDefinition->setArguments(['dummy_key', 'dummy_model']);
        $clientDefinition->addTag('llm_integration.ai_client', ['provider' => 'api_together']);
        $this->container->setDefinition('test.client', $clientDefinition);

        $this->pass->process($this->container);

        $this->assertTrue($this->container->hasDefinition('ai_client.api_together'));
        $this->assertTrue($this->container->hasDefinition('ai_client.main'));
        $this->assertTrue($this->container->hasAlias('Saqqal\LlmIntegrationBundle\Interface\AiClientInterface'));

        $mainClientDef = $this->container->getDefinition('ai_client.main');
        $this->assertEquals('%llm_integration.api_key%', $mainClientDef->getArgument('$apiKey'));
        $this->assertEquals('%llm_integration.model%', $mainClientDef->getArgument('$defaultModel'));
    }
}
