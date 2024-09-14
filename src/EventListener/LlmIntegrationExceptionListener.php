<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\EventListener;

use Psr\Log\LoggerInterface;
use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;

/**
 * This class listens for LlmIntegrationException events and logs them.
 *
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */
class LlmIntegrationExceptionListener
{
    private LoggerInterface $logger;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger The logger service to use for logging.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handles LlmIntegrationException events.
     *
     * Logs the exception message and additional information using the logger service.
     *
     * @param LlmIntegrationExceptionEvent $event The event containing the exception.
     *
     * @return void
     */
    public function onException(LlmIntegrationExceptionEvent $event): void
    {
        $exception = $event->getException();

        $this->logger->error('LLM Integration Exception: ' . $exception->getMessage(), [
            'exception' => $exception,
            'class' => get_class($exception),
        ]);
    }
}
