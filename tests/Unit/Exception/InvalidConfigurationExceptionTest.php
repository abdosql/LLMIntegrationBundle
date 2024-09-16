<?php

namespace Saqqal\LlmIntegrationBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Saqqal\LlmIntegrationBundle\Exception\InvalidConfigurationException;

class InvalidConfigurationExceptionTest extends TestCase
{
    public function testExceptionCreation(): void
    {
        $message = 'Test invalid configuration exception';
        $exception = new InvalidConfigurationException($message);

        $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertEquals('InvalidConfigurationException', InvalidConfigurationException::NAME);
    }

    public function testExceptionWithPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $message = 'Test invalid configuration exception with previous';
        $exception = new InvalidConfigurationException($message, $previousException);

        $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertSame($previousException, $exception->getPrevious());
    }
}
