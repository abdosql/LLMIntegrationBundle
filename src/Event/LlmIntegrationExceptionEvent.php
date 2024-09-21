<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Event;

use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * This class represents an event that is dispatched when an LlmIntegrationException occurs.
 * It provides a way to handle and react to such exceptions in a centralized manner.
 */
class LlmIntegrationExceptionEvent extends Event
{
    /**
     * Constructs a new LlmIntegrationExceptionEvent instance.
     *
     * @param LlmIntegrationException $exception The exception that occurred.
     */
    public function __construct(private readonly LlmIntegrationException $exception)
    {
    }

    /**
     * Returns the exception that occurred.
     *
     * @return LlmIntegrationException The exception that occurred.
     */
    public function getException(): LlmIntegrationException
    {
        return $this->exception;
    }
}
