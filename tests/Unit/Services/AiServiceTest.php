<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Interface\AiClientInterface;
use Saqqal\LlmIntegrationBundle\Response\AiResponse;
use Saqqal\LlmIntegrationBundle\Services\AiService;

class AiServiceTest extends TestCase
{
    private AiClientInterface $mockClient;
    private AiService $aiService;
    private string $defaultModel = 'default-model';
    private string $provider = 'test-provider';

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(AiClientInterface::class);
        $this->aiService = new AiService($this->mockClient, $this->defaultModel, $this->provider);
    }

    public function testGenerate(): void
    {
        $prompt = 'Test prompt';
        $expectedResponse = new AiResponse('success', ['content' => 'Generated response']);

        $this->mockClient->expects($this->once())
            ->method('sendPrompt')
            ->with($prompt, $this->defaultModel)
            ->willReturn($expectedResponse);

        $result = $this->aiService->generate($prompt);

        $this->assertSame($expectedResponse, $result);
    }

    public function testGenerateWithCustomModel(): void
    {
        $prompt = 'Test prompt';
        $customModel = 'custom-model';
        $expectedResponse = new AiResponse('success', ['content' => 'Generated response']);

        $this->mockClient->expects($this->once())
            ->method('sendPrompt')
            ->with($prompt, $customModel)
            ->willReturn($expectedResponse);

        $result = $this->aiService->generate($prompt, ['model' => $customModel]);

        $this->assertSame($expectedResponse, $result);
    }

    public function testGetProviderName(): void
    {
        $this->assertEquals('Test-provider', $this->aiService->getProviderName());
    }

    public function testGetCurrentModel(): void
    {
        $this->assertEquals($this->defaultModel, $this->aiService->getCurrentModel());
    }

    public function testSetModel(): void
    {
        $newModel = 'new-model';
        $this->aiService->setModel($newModel);

        $this->assertEquals($newModel, $this->aiService->getCurrentModel());
    }

    public function testGenerateAfterSetModel(): void
    {
        $newModel = 'new-model';
        $this->aiService->setModel($newModel);

        $prompt = 'Test prompt';
        $expectedResponse = new AiResponse('success', ['content' => 'Generated response']);

        $this->mockClient->expects($this->once())
            ->method('sendPrompt')
            ->with($prompt, $newModel)
            ->willReturn($expectedResponse);

        $result = $this->aiService->generate($prompt);

        $this->assertSame($expectedResponse, $result);
    }
}
