<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Expression;

/**
 * Method
 */
class Method
{
    /**
     * @var string
     */
    public $obj;
    /**
     * @var string
     */
    public $func;
    /**
     * @var bool
     */
    public $member;

    /**
     * @param string    $obj
     * @param string    $func
     * @param bool   $member
     */
    public function __construct($obj, $func, $member)
    {
        $this->obj = $obj;
        $this->func = $func;
        $this->member = $member;
    }

    /**
     * @return string
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * @param string $obj
     *
     * @return $this
     */
    public function setObj($obj)
    {
        $this->obj = $obj;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunc()
    {
        return $this->func;
    }

    /**
     * @param string $func
     *
     * @return $this
     */
    public function setFunc($func)
    {
        $this->func = $func;

        return $this;
    }

    /**
     * @return string
     */
    public function isMember()
    {
        return $this->member;
    }

    /**
     * @param bool|default $member
     *
     * @return $this
     */
    public function makeMember($member = true)
    {
        $this->member = $member;

        return $this;
    }
}
