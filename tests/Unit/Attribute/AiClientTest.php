<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

class AiClientTest extends TestCase
{
    public function testAiClientAttributeCreation(): void
    {
        $provider = 'test_provider';
        $aiClient = new AiClient($provider);

        $this->assertInstanceOf(AiClient::class, $aiClient);
        $this->assertEquals($provider, $aiClient->provider);
    }

    public function testAiClientAttributeUsage(): void
    {
        $reflectionClass = new \ReflectionClass(DummyAiClient::class);
        $attributes = $reflectionClass->getAttributes(AiClient::class);

        $this->assertCount(1, $attributes);
        $this->assertEquals(AiClient::class, $attributes[0]->getName());

        $instance = $attributes[0]->newInstance();
        $this->assertEquals('dummy_provider', $instance->provider);
    }
}

#[AiClient('dummy_provider')]
class DummyAiClient
{
}
