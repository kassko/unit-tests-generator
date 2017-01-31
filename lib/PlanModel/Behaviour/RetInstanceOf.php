<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Behaviour;

/**
 * RetInstanceOf
 */
class RetInstanceOf implements \Kassko\Test\UnitTestsGenerator\PlanModel\Behaviour
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
}
