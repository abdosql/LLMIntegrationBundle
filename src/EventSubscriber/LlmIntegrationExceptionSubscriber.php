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
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LlmIntegrationExceptionEvent::class => 'onCustomException',
        ];
    }

    public function onCustomException(LlmIntegrationExceptionEvent $event): void
    {
        $this->logLlmIntegrationException($event->getException());
    }

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
