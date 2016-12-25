<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Expression;

use Kassko\Test\UnitTestsGenerator\Model\Expression;
use Kassko\Test\UnitTestsGenerator\Model\Value;

/**
 * ReflPropertySet
 */
class ReflPropertySet implements Expression
{
    /**
     * @var Value
     */
    private $objectValue;
    /**
     * @var string
     */
    private $propertyName;
    /**
     * @var mixed
     */
    private $propertyValue;

    /**
     * @param Value    $objectValue
     * @param string            $propertyName
     * @param mixed             $propertyValue
     */
    public function __construct($objectValue, $propertyName, $propertyValue)
    {
        $this->objectValue = $objectValue;
        $this->propertyName = $propertyName;
        $this->propertyValue = $propertyValue;
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
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }
}
