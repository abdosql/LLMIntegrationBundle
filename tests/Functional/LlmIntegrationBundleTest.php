<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\LlmIntegrationBundle;
use Saqqal\LlmIntegrationBundle\DependencyInjection\LlmIntegrationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LlmIntegrationBundleTest extends TestCase
{
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $extension = new LlmIntegrationExtension();
        $this->container->registerExtension($extension);

        $bundle = new LlmIntegrationBundle();
        $bundle->build($this->container);

        $this->container->loadFromExtension('llm_integration', [
            'llm_provider' => 'api_together',
            'llm_api_key' => 'test_api_key',
            'llm_model' => 'test_model',
        ]);

        // We don't compile the container here
    }

    public function testExtensionLoaded(): void
    {
        $this->assertTrue($this->container->hasExtension('llm_integration'));
    }

    public function testParametersSet(): void
    {
        $this->assertTrue($this->container->hasParameter('llm_integration.provider'));
        $this->assertTrue($this->container->hasParameter('llm_integration.api_key'));
        $this->assertTrue($this->container->hasParameter('llm_integration.model'));
    }

    public function testServiceDefinitionsAdded(): void
    {
        $this->assertTrue($this->container->hasDefinition('ai_service.api_together') || $this->container->hasAlias('ai_service.api_together'));
        $this->assertTrue($this->container->hasDefinition('ai_client.api_together') || $this->container->hasAlias('ai_client.api_together'));
    }

    public function testInterfaceDefinitionsAdded(): void
    {
        $this->assertTrue($this->container->hasDefinition('Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface') || $this->container->hasAlias('Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface'));
        $this->assertTrue($this->container->hasDefinition('Saqqal\LlmIntegrationBundle\Interface\AiClientInterface') || $this->container->hasAlias('Saqqal\LlmIntegrationBundle\Interface\AiClientInterface'));
    }

    public function testCompilerPassesRegistered(): void
    {
        $passes = $this->container->getCompiler()->getPassConfig()->getPasses();
        $passClasses = array_map(function ($pass) {
            return get_class($pass);
        }, $passes);

        $this->assertContains('Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiClientConfigurationPass', $passClasses);
        $this->assertContains('Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiServiceAutoRegisterPass', $passClasses);
    }
}
