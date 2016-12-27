<?php

namespace Kassko\Test\UnitTestsGenerator;

/**
 * PlanProviderResource
 */
class PlanProviderResource
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param string $type
     * @param mixed  $resource
     */
    public function __construct($type, $resource)
    {
        $this->type = $type; 
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
