<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel;

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
