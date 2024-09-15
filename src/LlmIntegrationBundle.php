<?php

namespace Saqqal\LlmIntegrationBundle;

use Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiClientConfigurationPass;
use Saqqal\LlmIntegrationBundle\DependencyInjection\Compiler\AiServiceAutoRegisterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

/**
 * LlmIntegrationBundle integrates large language models (LLMs) into Symfony applications.
 * It supports multiple AI providers with a scalable architecture for easy addition of new providers.
 */

class LlmIntegrationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new AiServiceAutoRegisterPass());
        $container->addCompilerPass(new AiClientConfigurationPass());
    }
}
