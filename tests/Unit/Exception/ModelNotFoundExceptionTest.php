<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Exception\ModelNotFoundException;

class ModelNotFoundExceptionTest extends TestCase
{
    public function testExceptionCreation(): void
    {
        $message = 'Test model not found exception';
        $exception = new ModelNotFoundException($message);

        $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals('ModelNotFoundException', ModelNotFoundException::NAME);
    }

    public function testExceptionWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test model not found exception with previous';
        $exception = new ModelNotFoundException($message, $previousException);

        $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
