<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler;

use Saqqal\LlmIntegrationBundle\Attribute\AiServiceProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AiServiceAutoRegisterPass implements CompilerPassInterface
{
    private const SERVICE_ID_PREFIX = 'ai_service.';

    /**
     * Process the compiler pass.
     *
     * This method is called after all registered compiler passes have been
     * processed.
     *
     * @param ContainerBuilder $container The container builder
     *
     * @throws \ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $provider = $container->getParameter('llm_integration.provider');
        $services = $container->findTaggedServiceIds('llm_integration.ai_service');

        $this->configureServices($container, $services);
        $this->setMainService($container, $provider);
        $this->setAiServiceInterfaceNewAlias($container);
    }

    /**
     * Configure services with AiServiceProvider attribute.
     *
     * This method iterates over services tagged with 'llm_integration.ai_service'
     * and checks if they have the AiServiceProvider attribute. If found, it configures
     * the service and removes the old definition.
     *
     * @param ContainerBuilder $container The container builder
     * @param array            $services  Array of service ids tagged with 'llm_integration.ai_service'
     *
     * @throws \ReflectionException
     */
    public function configureServices(ContainerBuilder $container, array $services): void
    {
        foreach ($services as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);

            $serviceClass = $serviceDefinition->getClass();

            $reflectionClass = new \ReflectionClass($serviceClass);

            $attributes = $reflectionClass->getAttributes(AiServiceProvider::class);

            if (!empty($attributes)) {
                $this->configureService($container, $attributes, $serviceDefinition, $id);
            }
        }
    }

    /**
     * Configure a service with AiServiceProvider attribute.
     *
     * This method sets the arguments for the service based on the AiServiceProvider attribute.
     *
     * @param ContainerBuilder $container       The container builder
     * @param array            $attributes      Array of attributes
     * @param Definition       $serviceDefinition The service definition
     * @param string           $oldId            The old service id
     */
    public function configureService(ContainerBuilder $container, array $attributes, Definition $serviceDefinition, $oldId): void
    {
        $attributeInstance = $attributes[0]->newInstance();

        $serviceProvider = $attributeInstance->provider;

        $serviceId = self::SERVICE_ID_PREFIX . $serviceProvider;

        if (!$container->hasDefinition($serviceId)) {
            $container->setDefinition($serviceId, $serviceDefinition);
            $container->removeDefinition($oldId);
        }

        $serviceDefinition->setArgument('$client', new Reference('ai_client.' . $serviceProvider));
        $serviceDefinition->setArgument('$model', '%llm_integration.model%');
    }

    /**
     * Set the main AI service based on the provider.
     *
     * This method sets the 'ai_service.main' alias to the service corresponding to the given provider.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $provider  The AI service provider
     *
     * @throws \RuntimeException If no AI service is found for the given provider
     */
    public function setMainService(ContainerBuilder $container, string $provider): void
    {
        if ($container->hasDefinition(self::SERVICE_ID_PREFIX . $provider)) {
            $container->setDefinition('ai_service.main', $container->getDefinition(self::SERVICE_ID_PREFIX . $provider));
        } else {
            throw new \RuntimeException(sprintf('No AI Service found for provider "%s"', $provider));
        }
    }

    /**
     * Set a new alias for the AiServiceInterface.
     *
     * This method sets the 'Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface' alias
     * to the 'ai_service.main' service.
     *
     * @param ContainerBuilder $container The container builder
     */
    public function setAiServiceInterfaceNewAlias(ContainerBuilder $container): void
    {
        $container->setAlias(
            'Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface',
            new Reference('ai_service.main')
        );
    }
}
