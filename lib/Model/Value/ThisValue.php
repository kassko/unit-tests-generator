<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Value;

use Kassko\Test\UnitTestsGenerator\Model\AbstractSimpleValue;

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
