<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Exception\ProviderNotFoundException;

class ProviderNotFoundExceptionTest extends TestCase
{
    public function testExceptionCreation(): void
    {
        $message = 'Test provider not found exception';
        $exception = new ProviderNotFoundException($message);

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals('ProviderNotFoundException', ProviderNotFoundException::NAME);
    }

    public function testExceptionWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test provider not found exception with previous';
        $exception = new ProviderNotFoundException($message, $previousException);

        $this->assertInstanceOf(ProviderNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
