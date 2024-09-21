<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection;

use Saqqal\LlmIntegrationBundle\EventSubscriber\LlmIntegrationExceptionSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class LlmIntegrationExtension extends Extension
{
    private array $availableProviders = [];

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
     * Loads the services configurations from the services.yaml file.
     *
     * This function creates a new instance of the YamlFileLoader class, which is responsible for loading
     * the services configuration from the specified file. The FileLocator is used to locate the file in the
     * specified directory. Finally, the load method is called to actually load the services configuration.
     *
     * @param ContainerBuilder $container The container builder to register the services in.
     *
     * @return void
     * @throws \Exception
     */
    public function loadServicesConfigurations(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    /**
     * Registers the LlmIntegrationExceptionSubscriber service in the container.
     *
     * This subscriber listens for exceptions thrown during the application's runtime and logs them.
     *
     * @param ContainerBuilder $container The container builder to register the service in.
     *
     * @return void
     */
    private function registerExceptionSubscriber(ContainerBuilder $container): void
    {
        // Create a new Definition for the LlmIntegrationExceptionSubscriber class
        $subscriberDefinition = new Definition(LlmIntegrationExceptionSubscriber::class);

        // Set the 'logger' service as an argument for the subscriber
        $subscriberDefinition->setArgument('$logger', new Reference('logger'));

        // Tag the subscriber as a 'kernel.event_subscriber' to enable it during the event dispatching process
        $subscriberDefinition->addTag('kernel.event_subscriber');

        // Register the subscriber in the container with the 'llm_integration.exception_subscriber' id
        $container->setDefinition('llm_integration.exception_subscriber', $subscriberDefinition);
    }

}
