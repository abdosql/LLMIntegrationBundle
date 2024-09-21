<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class CreateAiServiceCommand extends Command
{
    protected static $defaultName = 'llm:create-ai-service';
    protected static $defaultDescription = 'Create a new AI service class';

    private string $projectDir;

    public function __construct(string $projectDir)
    {
        parent::__construct(self::$defaultName);
        $this->projectDir = $projectDir;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
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

        $content = $this->generateClassContent($className, $providerName, $endpoint);

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

    private function generateClassContent(string $className, string $providerName, string $endpoint): string
    {
        return <<<PHP
<?php

namespace Saqqal\LlmIntegrationBundle\Client;

use Saqqal\LlmIntegrationBundle\Attribute\AiClient;

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
