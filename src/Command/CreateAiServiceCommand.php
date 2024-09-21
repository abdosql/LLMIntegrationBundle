<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(name: 'llm:create-ai-service', description: "Create a new AI service class'")]
class CreateAiServiceCommand extends Command
{
    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the AI service')
            ->addArgument('endpoint', InputArgument::REQUIRED, 'The API endpoint for the service');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $endpoint = $input->getArgument('endpoint');

        $className = ucfirst($name) . 'Client';
        $providerName = strtolower($name);

        $namespace = 'App\\Client';

        $content = $this->generateClassContent($className, $providerName, $endpoint, $namespace);

        $filesystem = new Filesystem();
        $filePath = sprintf('%s/src/Client/%s.php', $this->projectDir, $className);
        try {
            $filesystem->dumpFile($filePath, $content);
            $io->success(sprintf('AI service "%s" created successfully at %s', $className, $filePath));
        } catch (\Exception $e) {
            $io->error(sprintf('Failed to create AI service: %s', $e->getMessage()));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function generateClassContent(string $className, string $providerName, string $endpoint, string $namespace): string
    {
        return <<<PHP
<?php

namespace $namespace;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Client\AbstractAiClient;

#[AiClient('$providerName')]
class $className extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return '$endpoint';
    }

    protected function getAdditionalRequestData(string \$prompt, ?string \$model): array
    {
        return [
            // Add any $className specific options here
        ];
    }
}

PHP;
    }
}
