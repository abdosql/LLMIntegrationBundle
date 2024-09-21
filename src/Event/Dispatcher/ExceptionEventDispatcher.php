<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Event\Dispatcher;

use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ExceptionEventDispatcher
 *
 * This class is responsible for dispatching LlmIntegrationExceptionEvents.
 * It utilizes Symfony's EventDispatcherInterface to handle the event dispatching.
 */
final class ExceptionEventDispatcher
{
    /**
     * ExceptionEventDispatcher constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher Symfony's EventDispatcherInterface instance.
     */
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Dispatches the given LlmIntegrationExceptionEvent.
     *
     * @param LlmIntegrationExceptionEvent $event The event to be dispatched.
     *
     * @return void
     */
    public function dispatchException(LlmIntegrationExceptionEvent $event): void
    {
        $this->eventDispatcher->dispatch($event, LlmIntegrationExceptionEvent::class);
    }
}
