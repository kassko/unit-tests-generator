<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Expression;

use Kassko\Test\UnitTestsGenerator\CodeModel\Expression;
use Kassko\Test\UnitTestsGenerator\CodeModel\Value;

/**
 * FuncCall
 */
class FuncCall implements Expression
{
    /**
     * @var Value
     */
    private $objectValue;
    /**
     * @var string
     */
    private $funcName;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var bool
     */
    private $fluent = false;

    /**
     * @param Value    $objectValue
     * @param string            $funcName
     * @param array             $parameters (default)
     */
    public function __construct(Value $objectValue, $funcName, array $parameters = [])
    {
        $this->objectValue = $objectValue;
        $this->funcName = $funcName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getObjectValue()
    {
        return $this->objectValue;
    }

    /**
     * @return string
     */
    public function getFuncName()
    {
        return $this->funcName;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function hasParameters()
    {
        return (bool)count($this->parameters);
    }

    /**
     * @param bool $fluent (optional)
     *
     * @return self
     */
    public function makeFluent($fluent = true)
    {
        $this->fluent = $fluent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFluent()
    {
        return $this->fluent;
    }
}
