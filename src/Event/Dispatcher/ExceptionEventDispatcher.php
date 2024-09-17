<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Event\Dispatcher;

use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ExceptionEventDispatcher
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatchException(LlmIntegrationExceptionEvent $event): void
    {
        $this->eventDispatcher->dispatch($event, LlmIntegrationExceptionEvent::class);
    }
}
