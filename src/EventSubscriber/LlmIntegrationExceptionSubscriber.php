<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\EventSubscriber;

use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Saqqal\LlmIntegrationBundle\Exception\LlmIntegrationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class LlmIntegrationExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger The logger service
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LlmIntegrationExceptionEvent::class => 'onCustomException',
        ];
    }

    /**
     * Logs the LlmIntegrationException.
     *
     * @param LlmIntegrationExceptionEvent $event The event that triggered this method
     */
    public function onCustomException(LlmIntegrationExceptionEvent $event): void
    {
        $this->logLlmIntegrationException($event->getException());
    }

    /**
     * Logs the LlmIntegrationException.
     *
     * @param LlmIntegrationException $exception The exception to log
     */
    private function logLlmIntegrationException(LlmIntegrationException $exception): void
    {
        $context = [
            'exception_class' => get_class($exception),
            'exception_name' => $exception::NAME,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        $this->logger->error($exception->getMessage(), $context);
    }
}
