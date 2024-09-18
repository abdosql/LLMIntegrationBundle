<?php
/**
 * @author Saqqal Abdelaziz <seqqal.abdelaziz@gmail.com>
 * @Linkedin https://www.linkedin.com/abdelaziz-saqqal
 */

namespace Saqqal\LlmIntegrationBundle\Response;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class DynamicAiResponse
 *
 * A dynamic response class that maps associative array keys to properties
 * and provides getters for each property.
 */
class DynamicAiResponse
{
    /**
     * @var array Holds the data for the response.
     */
    private array $data = [];

    /**
     * Populates the response data.
     *
     * @param array $data The data to populate the response with.
     */
    public function populate(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Magic method to handle dynamic getter calls.
     *
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     *
     * @return mixed The value of the property if it exists, otherwise throws a BadMethodCallException.
     */
    public function __call(string $name, array $arguments)
    {
        if (str_starts_with($name, 'get')) {
            $property = lcfirst(substr($name, 3));
            if (array_key_exists($property, $this->data)) {
                return $this->data[$property];
            }
            throw new \BadMethodCallException(sprintf('Undefined property "%s".', $property));
        }
        throw new \BadMethodCallException(sprintf('Undefined method "%s".', $name));
    }

    /**
     * Magic method to handle dynamic property access.
     *
     * @param string $name The name of the property being accessed.
     *
     * @return mixed The value of the property if it exists, otherwise throws a RuntimeException.
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        throw new \RuntimeException(sprintf('Undefined property "%s".', $name));
    }

    /**
     * Magic method to handle dynamic property assignment.
     *
     * @param string $name The name of the property being assigned.
     * @param mixed $value The value to assign to the property.
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic method to handle dynamic property existence check.
     *
     * @param string $name The name of the property being checked.
     *
     * @return bool True if the property exists, otherwise false.
     */
    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * Magic method to handle dynamic property unsetting.
     *
     * @param string $name The name of the property being unset.
     */
    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }

    /**
     * Returns the response data as an array.
     *
     * @return array The response data.
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
