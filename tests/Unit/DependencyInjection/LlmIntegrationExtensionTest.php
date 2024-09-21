<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\DependencyInjection\LlmIntegrationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LlmIntegrationExtensionTest extends TestCase
{
    private LlmIntegrationExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new LlmIntegrationExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad(): void
    {
        $config = [
            'llm_provider' => 'api_together',
            'llm_api_key' => 'test_key',
            'llm_model' => 'test_model',
        ];

        $this->extension->load([$config], $this->container);

        $this->assertTrue($this->container->hasParameter('llm_integration.provider'));
        $this->assertTrue($this->container->hasParameter('llm_integration.api_key'));
        $this->assertTrue($this->container->hasParameter('llm_integration.model'));

        $this->assertEquals('api_together', $this->container->getParameter('llm_integration.provider'));
        $this->assertEquals('test_key', $this->container->getParameter('llm_integration.api_key'));
        $this->assertEquals('test_model', $this->container->getParameter('llm_integration.model'));
    }
}
