<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\Expectation;

/**
 * Method
 */
class Method
{
    /**
     * @var Expectation[]
     */
    private $expectations;
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param Expectation[] $expectations (default)
     * @param bool          $enabled (default)
     */
    public function __construct(array $expectations = [], $enabled = true)
    {
        $this->expectations = $expectations;
        $this->enabled = $enabled;
    }

    /**
     * @param Expectation $expectation
     *
     * @return self
     */ 
    public function addExpectation(Expectation $expectation)
    {
        $this->expectations[] = $expectation;

        return $this;
    }

    /**
     * @return Expectation[]
     */
    public function getExpectations()
    {
        return $this->expectations;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enabled;
    }
}
