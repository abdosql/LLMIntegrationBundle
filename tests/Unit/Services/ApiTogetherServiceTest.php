<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Interface\AiClientInterface;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Saqqal\LlmIntegrationBundle\Services\ApiTogetherService;

class ApiTogetherServiceTest extends TestCase
{
    private $mockClient;
    private $apiTogetherService;

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(AiClientInterface::class);
        $this->apiTogetherService = new ApiTogetherService($this->mockClient, 'default_model');
    }

    public function testGenerate()
    {
        $expectedResponse = new AiResponse('success', ['content' => 'Test response']);

        $this->mockClient->expects($this->once())
            ->method('sendPrompt')
            ->with('Test prompt', 'default_model')
            ->willReturn($expectedResponse);

        $response = $this->apiTogetherService->generate('Test prompt');

        $this->assertInstanceOf(AiResponse::class, $response);
        $this->assertEquals('success', $response->getStatus());
        $this->assertEquals('Test response', $response->getData()['content']);
    }

    public function testGenerateWithCustomModel()
    {
        $expectedResponse = new AiResponse('success', ['content' => 'Custom model response']);

        $this->mockClient->expects($this->once())
            ->method('sendPrompt')
            ->with('Test prompt', 'custom_model')
            ->willReturn($expectedResponse);

        $response = $this->apiTogetherService->generate('Test prompt', ['model' => 'custom_model']);

        $this->assertInstanceOf(AiResponse::class, $response);
        $this->assertEquals('success', $response->getStatus());
        $this->assertEquals('Custom model response', $response->getData()['content']);
    }

    public function testGetProviderName()
    {
        $this->assertEquals('API Together', $this->apiTogetherService->getProviderName());
    }

    public function testGetAndSetCurrentModel()
    {
        $this->assertEquals('default_model', $this->apiTogetherService->getCurrentModel());

        $this->apiTogetherService->setModel('new_model');
        $this->assertEquals('new_model', $this->apiTogetherService->getCurrentModel());
    }
}
