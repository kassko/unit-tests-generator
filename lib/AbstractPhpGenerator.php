<?php

namespace Kassko\Test\UnitTestsGenerator;

/**
 * AbstractPhpGenerator
 */
abstract class AbstractPhpGenerator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
