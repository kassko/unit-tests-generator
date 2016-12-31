<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

/**
 * Expectation
 */
class Expectation
{
    /**
     * @var Value
     */
    private $result;
    /**
     * @var Path
     */
    private $path;
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param Value $result
     * @param Path  $path
     * @param bool  $enabled (default)
     */
    public function __construct(Value $result, Path $path, $enabled = true)
    {
        $this->result = $result;
        $this->path = $path;
        $this->enabled = $enabled;
    }

    /**
     * @return Value
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enabled;
    }
}
