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
        if (!$clients) {
            throw new AiClientException("No Ai Clients found");
        }

        $availableProviders = $this->collectProviders($container, $clients);
        $configuredProvider = $container->getParameter('llm_integration.provider');

        if (!in_array($configuredProvider, $availableProviders)) {
            throw new AiClientException(sprintf(
                "Invalid provider '%s'. Available providers are: %s",
                $configuredProvider,
                implode(', ', $availableProviders)
            ));
        }

        $this->configureClients($container, $clients);
        //        dd($container->getParameter('llm_integration.available_providers'));

    }

    /**
     * Configures AI clients based on their tagged services.
     *
     * This function iterates through the tagged services, retrieves their definitions,
     * and extracts the provider information from the AiClient attribute.
     * If the provider information is found, it configures the client definition by setting
     * the API key and default model arguments.
     *
     * @param ContainerBuilder $container The container builder to manipulate the AI client definitions.
     * @param array            $clients   The tagged service ids to be processed.
     *
     * @return void
     *
     * @throws \ReflectionException If the reflection of a class fails.
     */
    private function configureClients(ContainerBuilder $container, array $clients): void
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
     * Configures a single AI client based on its attributes.
     *
     * @param ContainerBuilder $container      The container builder to manipulate the AI client definitions.
     * @param array            $attributes     The attributes of the AI client, containing the provider information.
     * @param Definition       $clientDefinition The definition of the AI client to be configured.
     * @param string           $oldId          The old id of the AI client, used to identify the client in the container.
     *
     * @return void
     */
    private function configureClient(ContainerBuilder $container, array $attributes, Definition $clientDefinition, string $oldId): void
    {
        $attributeInstance = $attributes[0]->newInstance();
        $clientProvider = $attributeInstance->provider;

        $newId = self::CLIENT_ID_PREFIX . $clientProvider;

        // If the new id does not exist in the container, add the client definition with the new id and remove the old one
        if (!$container->hasDefinition($newId)) {
            $container->setDefinition($newId, $clientDefinition);
            $container->removeDefinition($oldId);
        }

        // Set the API key and default model arguments for the client definition
        $clientDefinition->setArgument('$apiKey', '%llm_integration.api_key%');
        $clientDefinition->setArgument('$defaultModel', '%llm_integration.model%');
    }

    /**
     * Collects the providers of AI clients from the tagged services.
     *
     * This function iterates through the tagged services, retrieves their definitions,
     * and extracts the provider information from the AiClient attribute.
     * The unique providers are then returned as an array.
     *
     * @param ContainerBuilder $container The container builder to retrieve service definitions.
     * @param array            $clients   The tagged service ids to be processed.
     *
     * @return array The unique providers of AI clients.
     *
     * @throws \ReflectionException If the reflection of a class fails.
     */
    private function collectProviders(ContainerBuilder $container, array $clients): array
    {
        $providers = [];
        foreach ($clients as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();
            $reflectionClass = new \ReflectionClass($class);
            $attribute = $reflectionClass->getAttributes(AiClient::class)[0] ?? null;

            if ($attribute) {
                $providers[] = $attribute->newInstance()->provider;
            }
        }
        return array_unique($providers);
    }
}
