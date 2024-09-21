<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
    }

    public function testGetConfigTreeBuilder(): void
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        $this->assertInstanceOf(TreeBuilder::class, $treeBuilder);
        $this->assertEquals('llm_integration', $treeBuilder->getRootNode()->getName());
    }

    public function testDefaultConfiguration(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, []);

        $this->assertArrayHasKey('llm_provider', $config);
        $this->assertArrayHasKey('llm_api_key', $config);
        $this->assertArrayHasKey('llm_model', $config);

        $this->assertEquals('api_together', $config['llm_provider']);
        $this->assertEquals('key', $config['llm_api_key']);
        $this->assertEquals('meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo', $config['llm_model']);
    }

    public function testFullConfiguration(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, [
            'llm_integration' => [
                'llm_provider' => 'openai',
                'llm_api_key' => 'test_api_key',
                'llm_model' => 'gpt-4',
            ]
        ]);

        $this->assertEquals('openai', $config['llm_provider']);
        $this->assertEquals('test_api_key', $config['llm_api_key']);
        $this->assertEquals('gpt-4', $config['llm_model']);
    }

    public function testInvalidProviderConfiguration(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);

        $processor = new Processor();
        $processor->processConfiguration($this->configuration, [
            'llm_integration' => [
                'llm_provider' => 'invalid_provider',
            ]
        ]);
    }
}
