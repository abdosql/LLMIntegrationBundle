<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 *  This class is responsible for configuring the LLM Integration bundle.
 *  It provides a configuration tree builder that allows for the definition of the bundle's settings.
 */

namespace Saqqal\LlmIntegrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        // Create a tree builder with the root name "llm_integration"
        $treeBuilder = new TreeBuilder("llm_integration");
        $rootNode = $treeBuilder->getRootNode();

        // Define the configuration settings
        $rootNode
            ->children()
                ->enumNode('llm_provider')
                    ->values(['api_together', 'openai','groq'])
                    ->cannotBeEmpty()
                    ->defaultValue('api_together')
                    ->isRequired()
                ->end()
                ->scalarNode('llm_api_key')
                    ->defaultValue('key')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('llm_model')
                    ->cannotBeEmpty()
                    ->defaultValue('meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo')
                    ->isRequired()
                ->end()
            ->end()
        ;

        // Return the tree builder
        return $treeBuilder;
    }
}
