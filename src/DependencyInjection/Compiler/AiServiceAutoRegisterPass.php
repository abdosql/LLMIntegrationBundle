<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AiServiceAutoRegisterPass implements CompilerPassInterface
{
    private const SERVICE_ID_PREFIX = 'ai_service.';

    /**
     * Process the container and register AI service definitions.
     *
     * @param ContainerBuilder $container The container builder
     */
    public function process(ContainerBuilder $container): void
    {
        $provider = $container->getParameter('llm_integration.provider');
        $services = $container->findTaggedServiceIds('llm_integration.ai_service');

        $this->configureService($container, $services, $provider);
        $this->setAiServiceInterfaceNewAlias($container, $provider);
    }

    /**
     * Configure the AI service definition based on the given provider.
     *
     * @param ContainerBuilder $container The container builder
     * @param array $services The tagged AI service definitions
     * @param string $serviceProvider The AI service provider
     */
    public function configureService(ContainerBuilder $container, array $services, string $serviceProvider): void
    {
        $serviceDefinition = $this->getServiceInstance($container, $services);
        $serviceId = self::SERVICE_ID_PREFIX . $serviceProvider;

        if (!$container->hasDefinition($serviceId)) {
            $container->setDefinition($serviceId, $serviceDefinition);
            $container->removeDefinition($serviceDefinition->getClass());
        }
        $serviceDefinition->setArgument('$client', new Reference('ai_client.' . $serviceProvider));
        $serviceDefinition->setArgument('$model', '%llm_integration.model%');
        $serviceDefinition->setArgument('$provider', $serviceProvider);
    }

    /**
     * Get the first service definition from the given array.
     *
     * @param ContainerBuilder $container The container builder
     * @param array $services The tagged AI service definitions
     * @return Definition The first service definition
     */
    public function getServiceInstance(ContainerBuilder $container, array $services): Definition
    {
        $serviceDefinition = new Definition();
        foreach ($services as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);
        }
        return $serviceDefinition;
    }

    /**
     * Set a new alias for the AiServiceInterface.
     *
     * This method sets the 'Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface' alias
     * to the 'ai_service.main' service.
     *
     * @param ContainerBuilder $container The container builder
     * @param string $provider The AI service provider
     */
    public function setAiServiceInterfaceNewAlias(ContainerBuilder $container, string $provider): void
    {
        $container->setAlias(
            'Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface',
            new Reference(self::SERVICE_ID_PREFIX . $provider)
        );
    }
}
