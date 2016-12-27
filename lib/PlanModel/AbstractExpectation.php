<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

/**
 * AbstractExpectation
 */
abstract class AbstractExpectation
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param bool $enabled (default)
     */
    public function __construct($enabled = true)
    {
        $this->enabled = $enabled;
    }   

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enabled;
    }
}
