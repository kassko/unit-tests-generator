<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\ActivableTrait;

/**
 * Property
 */
class Property
{
    use ActivableTrait;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $semanticType;

    /**
     * @param string    $name
     * @param string    $semanticType
     */
    public function __construct($name, $semanticType)
    {
        $this->name = $name;
        $this->semanticType = $semanticType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSemanticType()
    {
        return $this->semanticType;
    }
}
