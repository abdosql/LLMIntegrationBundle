<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Exception\AiClientException;

class AiClientExceptionTest extends TestCase
{
    public function testExceptionCreation(): void
    {
        $message = 'Test AI client exception';
        $exception = new AiClientException($message);

        $this->assertInstanceOf(AiClientException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals('ai-client', AiClientException::NAME);
    }

    public function testExceptionWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test AI client exception with previous';
        $exception = new AiClientException($message, $previousException);

        $this->assertInstanceOf(AiClientException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
