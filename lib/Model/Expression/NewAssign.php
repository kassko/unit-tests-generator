<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Expression;

use Kassko\Test\UnitTestsGenerator\Model\Expression;
use Kassko\Test\UnitTestsGenerator\Model\Parameter;
use Kassko\Test\UnitTestsGenerator\Model\Value;

/**
 * NewAssign
 */
class NewAssign implements Expression
{
    /**
     * @var Value
     */
    private $objectValue;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $fullClass;
    /**
     * @var boolean
     */
    private $constructor;
    /**
     * @var boolean
     */
    private $stub = false;
    /**
     * @var Parameter[]
     */
    private $parameters = [];

    /**
     * @param Value     $objectValue
     * @param string    $class
     * @param string    $fullClass
     */
    public function __construct(Value $objectValue, $class, $fullClass)
    {
        $this->objectValue = $objectValue;
        $this->class = $class;
        $this->fullClass = $fullClass;
    }

    /**
     * @return Value
     */
    public function getObjectValue()
    {
        return $this->objectValue;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getFullClass()
    {
        return $this->fullClass;
    }

    /**
     * @param string $constructor (default)
     *
     * @return self
     */
    public function withConstructor($constructor = true)
    {
        $this->constructor = $constructor;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasConstructor()
    {
        return $this->constructor;
    }

    /**
     * @param Parameter $parameter
     *
     * @return self
     */
    public function addParameter($parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @param Parameter[] $parameters
     *
     * @return self
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $stub (default)
     *
     * @return self
     */
    public function makeStub($stub = true)
    {
        $this->stub = $stub;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStub()
    {
        return $this->stub;
    }
}
