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

class AiClientConfigurationPass implements CompilerPassInterface
{
    private const CLIENT_ID_PREFIX = 'ai_client.';

    /**
     * Process the container and configure AI clients based on their tagged services and provider parameter.
     *
     * @param ContainerBuilder $container The container builder
     *
     * @throws \ReflectionException
     * @throws AiClientException
     */
    public function process(ContainerBuilder $container): void
    {
        $clients = $container->findTaggedServiceIds('llm_integration.ai_client');
        $provider = $container->getParameter('llm_integration.provider');
        if (!$clients) {
            throw new AiClientException("No Ai Clients found");
        }
        $this->configureClients($container, $clients);
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
     * @param string $oldId          The old id of the AI client
     */
    public function configureClient(ContainerBuilder $container, array $attributes, Definition $clientDefinition, string $oldId): void
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
}
