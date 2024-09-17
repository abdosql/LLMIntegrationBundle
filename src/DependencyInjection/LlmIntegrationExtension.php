<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection;

use Saqqal\LlmIntegrationBundle\EventSubscriber\LlmIntegrationExceptionListener;
use Saqqal\LlmIntegrationBundle\EventSubscriber\LlmIntegrationExceptionSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
        $this->setGlobalParameters($config, $container);

        // Load services configuration from services.yaml file
        $this->loadServicesConfigurations($container);

        $this->registerExceptionSubscriber($container);
    }

    /**
     * Sets global parameters for the extension.
     *
     * @param array            $config     The configuration values
     * @param ContainerBuilder $container  The container builder
     */
    public function setGlobalParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('llm_integration.provider', $config['llm_provider']);
        $container->setParameter('llm_integration.api_key', $config['llm_api_key']);
        $container->setParameter('llm_integration.model', $config['llm_model']);
    }

    /**
     * @throws \Exception
     */
    public function loadServicesConfigurations(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    private function registerExceptionSubscriber(ContainerBuilder $container): void
    {
        $subscriberDefinition = new Definition(LlmIntegrationExceptionSubscriber::class);
        $subscriberDefinition->setArgument('$logger', new Reference('logger'));
        $subscriberDefinition->addTag('kernel.event_subscriber');
        $container->setDefinition('llm_integration.exception_subscriber', $subscriberDefinition);
    }
}
