<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Behaviour;

/**
 * RetVal
 */
class RetVal implements \Kassko\Test\UnitTestsGenerator\PlanModel\Behaviour
{
    /**
     * @var mixed
     */
    private $val;

    /**
     * @param mixed $val
     */
    public function __construct($val)
    {
        $this->val = $val;
    }
}
