<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\Method;

/**
 * Class_
 */
class Class_
{
    /**
     * @var Method[]
     */
    private $methods;
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param Method[]      $methods (default)
     * @param bool          $enabled (default)
     */
    public function __construct(array $methods = [], $enabled = true)
    {
        $this->methods = $methods;
        $this->enabled = $enabled;
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
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enabled;
    }
}
