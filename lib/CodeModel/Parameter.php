<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel;

use Kassko\Test\UnitTestsGenerator\CodeModel\Value;

/**
 * Parameter
 */
class Parameter
{
    /**
     * @var Value
     */
    private $value;
    /**
     * @var string
     */
    private $parentObjectName;
    /**
     * @var string
     */
    private $parentPropertyName;

    /**
     * @param Value    $value
     * @param string            $parentObjectName   (default)
     * @param string            $parentPropertyName (default)
     */
    public function __construct(Value $value, $parentObjectName = null, $parentPropertyName = null)
    {
        $this->value = $value;
        $this->parentObjectName = $parentObjectName;
        $this->parentPropertyName = $parentPropertyName;
    }

    /**
     * @param Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $parentObjectName
     */
    public function getParentObjectName()
    {
        return $this->parentObjectName;
    }

    /**
     * @param string $parentPropertyName
     */
    public function getParentPropertyName()
    {
        return $this->parentPropertyName;
    }
}
