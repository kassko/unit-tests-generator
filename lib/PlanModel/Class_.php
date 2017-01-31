<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\ActivableTrait;
use Kassko\Test\UnitTestsGenerator\PlanModel\Method;

/**
 * Class_
 */
class Class_
{
    use ActivableTrait;

    /**
     * @var Property[]
     */
    private $properties;
    /**
     * @var Method[]
     */
    private $methods;

    /**
     * @param Method[]      $methods (default)
     */
    public function __construct(array $methods = [])
    {
        $this->methods = $methods;
    }

    /**
     * @param string $name
     * @return Property
     */
    public function getProperty($name)
    {
        if (!isset($this->properties[$name])) {
            throw new \DomainException(sprintf('The property "%s" do not exist.', $name));
        }

        return $this->properties[$name];
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $name
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty($name, Property $prop)
    {
        $this->properties[$name] = $prop;

        return $this;
    }

    /**
     * @param Property[] $property
     *
     * @return $this
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Property
     */
    public function getMethod($name)
    {
        if (!isset($this->methods[$name])) {
            throw new \DomainException(sprintf('The method "%s" do not exist.', $name));
        }

        return $this->methods[$name];
    }

    /**
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Method $method
     *
     * @return $this
     */
    public function addMethod(Method $method)
    {
        $this->methods[] = $method;

        return $this;
    }

    /**
     * @param Method[] $method
     *
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }
}
