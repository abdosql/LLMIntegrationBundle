<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/*
 * This command is used to create a new AI service class.
*/

#[AsCommand(name: 'llm:create-ai-service', description: "Create a new AI service class'")]
class CreateAiServiceCommand extends Command
{
    /**
     * Constructor.
     *
     * @param string $projectDir The project directory path
     */
    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    /**
     * Executes the command to create a new AI service class.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return int 0 if everything went fine, or an error code
     *
     * @throws \RuntimeException If the provider name or API endpoint are invalid
     * @throws \Exception If there is an error creating the AI service file
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $io->warning('You are about to create a new AI Client. Please proceed with caution.');

        // Provider name validation
        $nameQuestion = new Question('Please enter the provider name: ');
        $nameQuestion->setValidator(function ($answer) {
            if (!is_string($answer) || trim($answer) === '') {
                throw new \RuntimeException('Provider name must be a non-empty string.');
            }
            return $answer;
        });
        $name = $helper->ask($input, $output, $nameQuestion);

        $io->success('Great! One step left.');

        // API endpoint validation
        $endpointQuestion = new Question('Please enter the API endpoint: ');
        $endpointQuestion->setValidator(function ($answer) {
            if (!is_string($answer) || trim($answer) === '') {
                throw new \RuntimeException('API endpoint must be a non-empty string.');
            }
            if (!filter_var($answer, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('API endpoint must be a valid URL.');
            }
            return $answer;
        });
        $endpoint = $helper->ask($input, $output, $endpointQuestion);

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
    /**
     * Generates the content of the AI service class file.
     *
     * @param string $className   The name of the AI service class
     * @param string $providerName The name of the AI service provider
     * @param string $endpoint    The API endpoint of the AI service
     * @param string $namespace   The namespace of the AI service class
     *
     * @return string The content of the AI service class file
     */
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
