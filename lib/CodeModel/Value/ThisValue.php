<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Value;

use Kassko\Test\UnitTestsGenerator\CodeModel\AbstractSimpleValue;

/**
 * ThisValue
 */
class ThisValue extends AbstractSimpleValue
{
    /**
     * @{inheritdoc}
     */
    public function getAsScalar()
    {
        return '$this';
    }
}
