# 🤖 LLMIntegrationBundle

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E6.0%7C%5E7.0-000000.svg)](https://symfony.com/)

LLMIntegrationBundle is a powerful Symfony bundle that seamlessly integrates Large Language Models (LLMs) into your Symfony applications. With support for multiple AI providers and a flexible architecture, it's designed for easy extension and customization.

## 📚 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Configuration](#️-configuration)
- [Usage](#-usage)
- [Available AI Clients](#-available-ai-clients)
- [CLI Commands](#-cli-commands)
- [Extending the Bundle](#-extending-the-bundle)
- [Exception Handling](#-exception-handling)
- [Testing](#-testing)
- [License](#-license)
- [Author](#-author)
- [Contributing](#-contributing)
- [Documentation](#-documentation)
- [Acknowledgements](#-acknowledgements)

## ✨ Features

- 🌐 Support for multiple AI providers
- ⚙️ Flexible configuration
- 🛡️ Exception handling with custom exceptions
- 🖥️ CLI integration for generating new AI service classes
- 🧩 Extensible architecture
- 🧪 Comprehensive unit testing

## 📦 Installation

Install the bundle using Composer:

```bash
composer require saqqal/llm-integration-bundle
```

## 🛠️ Configuration

1. Register the bundle in `config/bundles.php`:

```php
<?php
return [
    // ...
    Saqqal\LlmIntegrationBundle\LlmIntegrationBundle::class => ['all' => true],
];
```

2. Create `config/packages/llm_integration.yaml`:

```yaml
llm_integration:
    llm_provider: 'api_together'
    llm_api_key: '%env(LLM_PROVIDER_API_KEY)%'
    llm_model: 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo'
```

3. Set the API key in your `.env` file:

```
LLM_PROVIDER_API_KEY=your_api_key_here
```

## 🚀 Usage

### Injecting the AI Service

Inject `AiServiceInterface` into your services or controllers:

```php
use Saqqal\LlmIntegrationBundle\Interface\AiServiceInterface;

class YourService
{
    private AiServiceInterface $aiService;

    public function __construct(AiServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    // ...
}
```

### Generating Responses

Use the `generate` method to send prompts and receive responses:

```php
public function generateResponse(string $prompt): string
{
    $response = $this->aiService->generate($prompt);
    return $response->getData()['content'];
}
```

### Changing Output Type

You can change the output type to `DynamicAiResponse` for more flexible access to API responses:

```php
public function generateDynamicResponse(string $prompt): mixed
{
    $response = $this->aiService->generate($prompt, [], true);
    return $response->choices[0]->message->content;
}
```

## 🤝 Available AI Clients

LLMIntegrationBundle supports the following AI clients:

1. API Together (`ApiTogetherClient`)
2. OpenAI (`OpenAiClient`)
3. Anthropic (`AnthropicClient`)
4. Arliai (`ArliaiClient`)
5. Deepinfra (`DeepinfraClient`)
6. Groq (`GroqClient`)
7. HuggingFace (`HuggingFaceClient`)
8. Mistral (`MistralClient`)
9. OpenRouter (`OpenRouterClient`)
10. Tavily (`TavilyClient`)

To use a specific client, set the `llm_provider` in your configuration to the corresponding provider name.

## 💻 CLI Commands

### Generate a new AI service class

```bash
php bin/console llm:create-ai-service
```

Follow the prompts to enter the provider name and API endpoint.

### List available AI clients

```bash
php bin/console llm:list-ai-services
```

This command will list all available AI clients that are tagged with the `@AiClient` attribute.

## 🔧 Extending the Bundle

To add a new AI provider:

1. Create a new client class extending `AbstractAiClient`:

```php
use Saqqal\LlmIntegrationBundle\Attribute\AiClient;
use Saqqal\LlmIntegrationBundle\Client\AbstractAiClient;

#[AiClient('your_provider')]
class YourProviderClient extends AbstractAiClient
{
    protected function getApiUrl(): string
    {
        return 'https://api.yourprovider.com/v1/chat/completions';
    }

    protected function getAdditionalRequestData(string $prompt, ?string $model): array
    {
        return [
            // Add provider-specific options here
        ];
    }
}
```

2. Update your configuration to use the new provider:

```yaml
llm_integration:
    llm_provider: 'your_provider'
    llm_api_key: '%env(YOUR_PROVIDER_API_KEY)%'
    llm_model: 'your-default-model'
```

## 🚦 Exception Handling

Create an event subscriber to handle `LlmIntegrationExceptionEvent`:

```php
use Saqqal\LlmIntegrationBundle\Event\LlmIntegrationExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LlmIntegrationExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LlmIntegrationExceptionEvent::class => 'onLlmIntegrationException',
        ];
    }

    public function onLlmIntegrationException(LlmIntegrationExceptionEvent $event): void
    {
        $exception = $event->getException();
        // Handle the exception
    }
}
```

## 🧪 Testing

Run the test suite:

```bash
./vendor/bin/phpunit
```

## 📄 License

This bundle is released under the MIT License. See the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

Abdelaziz Saqqal - [LinkedIn](https://www.linkedin.com/in/saqqal-abdelaziz/) - [Portfolio](https://seqqal.vercel.app/)

## 🤝 Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## 📚 Documentation

For more detailed documentation, please visit our [Wiki]().
