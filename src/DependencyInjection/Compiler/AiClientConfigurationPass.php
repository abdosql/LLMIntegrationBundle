<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Exception\AiClientException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AiClientConfigurationPass implements CompilerPassInterface
{
    private const CLIENT_ID_PREFIX = 'ai_client.';

    /**
     * Process the container and configure AI clients based on their tagged services and provider parameter.
     *
     * @param ContainerBuilder $container The container builder
     *
     * @throws \ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $clients = $container->findTaggedServiceIds('llm_integration.ai_client');
        if (!$clients) {
            // Handle exceptions and dispatch an event
            try {
                throw new ('No Services tagged with llm_integration.ai_client found');
            } catch (AiClientException $e) {
                //                $event = new LlmIntegrationExceptionEvent($e);
                //                $this->eventDispatcher->dispatch($event, AiClientException::NAME);
            }
        }
        $provider = $container->getParameter('llm_integration.provider');

        $this->configureClients($container, $clients);
        $this->setMainClient($container, $provider);
        $this->setAiClientInterfaceNewAlias($container);
    }

    /**
     * Configure AI clients based on their tagged services.
     *
     * @param ContainerBuilder $container The container builder
     * @param array            $clients   The tagged service ids
     *
     * @throws \ReflectionException
     */
    public function configureClients(ContainerBuilder $container, array $clients): void
    {
        foreach ($clients as $id => $tags) {
            $clientDefinition = $container->getDefinition($id);

            $clientClass = $clientDefinition->getClass();

            $reflectionClass = new \ReflectionClass($clientClass);

            $attributes = $reflectionClass->getAttributes(AiClient::class);

            if (!empty($attributes)) {

                $this->configureClient($container, $attributes, $clientDefinition, $id);
            }
        }
    }

    /**
     * Configure a single AI client based on its attributes.
     *
     * @param ContainerBuilder $container      The container builder
     * @param array            $attributes     The attributes of the AI client
     * @param Definition       $clientDefinition The definition of the AI client
     * @param string           $oldId          The old id of the AI client
     */
    public function configureClient(ContainerBuilder $container, array $attributes, Definition $clientDefinition, $oldId): void
    {
        $attributeInstance = $attributes[0]->newInstance();

        $clientProvider = $attributeInstance->provider;

        $newId = self::CLIENT_ID_PREFIX . $clientProvider;

        if (!$container->hasDefinition($newId)) {
            $container->setDefinition($newId, $clientDefinition);
            $container->removeDefinition($oldId);
        }

        $clientDefinition->setArgument('$apiKey', '%llm_integration.api_key%');
        $clientDefinition->setArgument('$defaultModel', '%llm_integration.model%');
    }

    /**
     * Set the main AI client based on the provider parameter.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $provider  The provider of the AI client
     *
     * @throws \RuntimeException If no AI client is found for the given provider
     */
    public function setMainClient(ContainerBuilder $container, string $provider): void
    {
        if ($container->hasDefinition(self::CLIENT_ID_PREFIX . $provider)) {
            $container->setDefinition('ai_client.main', $container->getDefinition(self::CLIENT_ID_PREFIX . $provider));
        } else {
            throw new \RuntimeException(sprintf('No AI client found for provider "%s"', $provider));
        }
    }

    /**
     * Set a new alias for the AiClientInterface to the main AI client.
     *
     * @param ContainerBuilder $container The container builder
     */
    public function setAiClientInterfaceNewAlias(ContainerBuilder $container): void
    {
        $container->setAlias(
            'Saqqal\LlmIntegrationBundle\Interface\AiClientInterface',
            new Reference('ai_service.main')
        );
    }
}
