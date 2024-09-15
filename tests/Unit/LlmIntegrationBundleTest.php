<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Tests;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Attribute\AiServiceProvider;
use Saqqal\LlmIntegrationBundle\DependencyInjection\Configuration;
use Saqqal\LlmIntegrationBundle\DependencyInjection\LlmIntegrationExtension;
use Saqqal\LlmIntegrationBundle\LlmIntegrationBundle;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class LlmIntegrationBundleTest extends TestCase
{
    private Configuration $configuration;
    private LlmIntegrationExtension $extension;
    private LlmIntegrationBundle $bundle;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->extension = new LlmIntegrationExtension();
        $this->bundle = new LlmIntegrationBundle();
    }

    public function testConfiguration(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, [
            [
                'llm_provider' => 'api_together',
                'llm_api_key' => 'test_key',
                'llm_model' => 'test_model',
            ]
        ]);

        $this->assertArrayHasKey('llm_provider', $config);
        $this->assertArrayHasKey('llm_api_key', $config);
        $this->assertArrayHasKey('llm_model', $config);
    }

    public function testExtension(): void
    {
        $container = new ContainerBuilder();
        $this->extension->load([
            'llm_integration' => [
                'llm_provider' => 'api_together',
                'llm_api_key' => 'test_key',
                'llm_model' => 'test_model',
            ]
        ], $container);

        $this->assertTrue($container->hasParameter('llm_integration.provider'));
        $this->assertTrue($container->hasParameter('llm_integration.api_key'));
        $this->assertTrue($container->hasParameter('llm_integration.model'));
    }

    public function testBundleCompilerPasses(): void
    {
        $container = new ContainerBuilder();
        $this->bundle->build($container);

        $passes = $container->getCompiler()->getPassConfig()->getPasses();
        $passClasses = array_map(function ($pass) {
            return get_class($pass);
        }, $passes);

        $this->assertContains('Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiClientConfigurationPass', $passClasses);
        $this->assertContains('Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiServiceAutoRegisterPass', $passClasses);
    }

    public function testAiClientAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(DummyAiClient::class);
        $attributes = $reflectionClass->getAttributes(AiClient::class);

        $this->assertCount(1, $attributes);
        $this->assertEquals(AiClient::class, $attributes[0]->getName());

        $instance = $attributes[0]->newInstance();
        $this->assertEquals('dummy_provider', $instance->provider);
    }

    public function testAiServiceProviderAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(DummyAiServiceProvider::class);
        $attributes = $reflectionClass->getAttributes(AiServiceProvider::class);

        $this->assertCount(1, $attributes);
        $this->assertEquals(AiServiceProvider::class, $attributes[0]->getName());

        $instance = $attributes[0]->newInstance();
        $this->assertEquals('dummy_service_provider', $instance->provider);
    }
}

#[AiClient('dummy_provider')]
class DummyAiClient
{
}

#[AiServiceProvider('dummy_service_provider')]
class DummyAiServiceProvider
{
}
