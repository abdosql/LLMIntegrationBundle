<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AiClient
{
    /**
     * Constructor for AiClient class.
     *
     * @param string $provider The name of the AI client provider.
     */
    public function __construct(
        public string $provider
    ) {
    }
}
