<?php

namespace Kassko\Test\UnitTestsGenerator\Model;

/**
 * AbstractSimpleValue
 */
abstract class AbstractSimpleValue extends AbstractValue
{
    /**
     * @return mixed
     */
    abstract public function getAsScalar();
}
