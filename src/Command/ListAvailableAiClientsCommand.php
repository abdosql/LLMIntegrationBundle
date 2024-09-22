<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Command;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand(name: 'llm:list-ai-services', description: 'List all available AI services.')]
class ListAvailableAiClientsCommand extends Command
{
    /**
     * Constructor for the command.
     *
     * @param array $aiClients An iterable of AI clients tagged with 'llm_integration.ai_client'
     */
    public function __construct(
        #[TaggedIterator('llm_integration.ai_client')] private readonly iterable $aiClients
    ) {
        parent::__construct();
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input  The input interface
     * @param OutputInterface $output The output interface
     *
     * @return int The exit code
     *
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Available AI Clients');

        $clients = [];
        foreach ($this->aiClients as $client) {
            $reflection = new \ReflectionClass($client);
            $attribute = $reflection->getAttributes(AiClient::class)[0] ?? null;

            if ($attribute) {
                $provider = $attribute->newInstance()->provider;
                $clients[] = [get_class($client), $provider];
            }
        }

        if (empty($clients)) {
            $io->warning('No AI clients found.');
        } else {
            $io->table(['Class', 'Provider'], $clients);
        }

        return Command::SUCCESS;
    }
}
