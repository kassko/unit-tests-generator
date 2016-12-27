<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Value;

use Kassko\Test\UnitTestsGenerator\CodeModel\AbstractSimpleValue;

/**
 * HighValue
 */
class HighValue extends AbstractSimpleValue
{
    /**
     * @{inheritdoc}
     */
    public function getAsScalar()
    {
        return $this->value;
    }
}
