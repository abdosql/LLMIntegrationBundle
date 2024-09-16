<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;

class LlmIntegrationExceptionTest extends TestCase
{
    public function testExceptionCreation(): void
    {
        $message = 'Test LLM integration exception';
        $exception = new ConcreteLlmIntegrationException($message);

        $this->assertInstanceOf(LlmIntegrationException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }

    public function testExceptionWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test LLM integration exception with previous';
        $exception = new ConcreteLlmIntegrationException($message, 0, $previousException);

        $this->assertInstanceOf(LlmIntegrationException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}

class ConcreteLlmIntegrationException extends LlmIntegrationException
{
    // Concrete implementation for testing
}
