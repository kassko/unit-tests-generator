<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Statement\Spy;

use Kassko\Test\UnitTestsGenerator\CodeModel\Statement\Spy;

/**
 * MethodSpy
 */
class MethodSpy implements Spy
{
    /**
     * @var string
     */
    private $methodName;
    /**
     * @var string
     */
    private $call;
    /**
     * @var string
     */
    private $return;

    /**
     * @param string $methodName
     * @param string $call
     * @param string $return
     */
    public function __construct($methodName, $call, $return)
    {
        $this->methodName = $methodName;
        $this->call = $call;
        $this->return = $return;
    }

    /**
     * @param string $methodName
     *
     * @return $this
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $call
     *
     * @return $this
     */
    public function setCall($call)
    {
        $this->call = $call;

        return $this;
    }

    /**
     * @return string
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @param string $return
     *
     * @return $this
     */
    public function setReturn($return)
    {
        $this->return = $return;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }
}
