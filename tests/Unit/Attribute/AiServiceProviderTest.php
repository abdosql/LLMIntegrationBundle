<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Attribute\AiServiceProvider;

class AiServiceProviderTest extends TestCase
{
    public function testAiServiceProviderAttributeCreation(): void
    {
        $provider = 'test_provider';
        $aiServiceProvider = new AiServiceProvider($provider);

        $this->assertInstanceOf(AiServiceProvider::class, $aiServiceProvider);
        $this->assertEquals($provider, $aiServiceProvider->provider);
    }

    public function testAiServiceProviderAttributeUsage(): void
    {
        $reflectionClass = new \ReflectionClass(DummyAiServiceProvider::class);
        $attributes = $reflectionClass->getAttributes(AiServiceProvider::class);

        $this->assertCount(1, $attributes);
        $this->assertEquals(AiServiceProvider::class, $attributes[0]->getName());

        $instance = $attributes[0]->newInstance();
        $this->assertEquals('dummy_service_provider', $instance->provider);
    }
}

#[AiServiceProvider('dummy_service_provider')]
class DummyAiServiceProvider
{
}
