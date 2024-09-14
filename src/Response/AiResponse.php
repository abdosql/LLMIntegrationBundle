<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Response;

class AiResponse
{
    /**
     * @var string The status of the AI response.
     */
    private string $status;

    /**
     * @var array The data returned by the AI model.
     */
    private array $data;

    /**
     * @var ?array Additional metadata about the AI response.
     */
    private ?array $metadata;

    /**
     * AiResponse constructor.
     *
     * @param string $status The status of the AI response.
     * @param array $data The data returned by the AI model.
     * @param array|null $metadata Additional metadata about the AI response.
     */
    public function __construct(string $status, array $data, ?array $metadata = null)
    {
        $this->status = $status;
        $this->data = $data;
        $this->metadata = $metadata;
    }

    /**
     * Returns the status of the AI response.
     *
     * @return string The status of the AI response.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Returns the data returned by the AI model.
     *
     * @return array The data returned by the AI model.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns additional metadata about the AI response.
     *
     * @return ?array Additional metadata about the AI response.
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * Retrieves a specific value from the data array using a key.
     * If the key does not exist, it returns the default value.
     *
     * @param string $key The key to retrieve the value from the data array.
     * @param mixed|null $default The default value to return if the key does not exist.
     * @return mixed The value associated with the key or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}
