<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Factory;

use Saqqal\LlmIntegrationBundle\Exception\ProviderNotFoundException;
use Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AiServiceFactory
{
    private const SERVICE_ID_PREFIX = 'ai_service.';

    /**
     * Constructor.
     *
     * @param ContainerInterface $container Symfony's container interface
     */
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * Creates an AI service based on the given provider.
     *
     * @param string $provider The name of the AI service provider
     *
     * @return AiServiceInterface The created AI service
     *
     * @throws ProviderNotFoundException If the AI service does not exist
     * @throws \RuntimeException If the service does not implement AiServiceInterface
     */
    public function create(string $provider): AiServiceInterface
    {
        $serviceId = self::SERVICE_ID_PREFIX . $provider;
        if (!$this->container->has($serviceId)) {
            throw new ProviderNotFoundException(sprintf('The AI service "%s" does not exist.', $serviceId));
        }

        $service = $this->container->get($serviceId);

        if (!$service instanceof AiServiceInterface) {
            throw new \RuntimeException(sprintf('Service "%s" must implement AiServiceInterface.', $serviceId));
        }

        return $service;
    }
}
