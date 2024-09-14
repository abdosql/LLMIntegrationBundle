<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection;

use Saqqal\LlmIntegrationBundle\Factory\AiServiceFactory;
use Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class LlmIntegrationExtension extends Extension
{
    /**
     * Loads the configuration for the LlmIntegrationExtension extension.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \Exception If something went wrong during the loading process
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Set parameters for the extension
        $container->setParameter('llm_integration.provider', $config['llm_provider']);
        $container->setParameter('llm_integration.api_key', $config['llm_api_key']);
        $container->setParameter('llm_integration.model', $config['llm_model']);

        // Load services configuration from services.yaml file
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        // Register the AiServiceFactory
        $container->register('llm_integration.ai_service_factory', AiServiceFactory::class)
            ->setArgument('$container', new Reference('service_container'));

        // Register the main AiService using the AiServiceFactory
        $container->register('ai_service.main', AiServiceInterface::class)
            ->setFactory([new Reference('llm_integration.ai_service_factory'), 'create'])
            ->setArgument('$provider', '%llm_integration.provider%');
    }
}
