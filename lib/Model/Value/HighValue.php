<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Value;

use Kassko\Test\UnitTestsGenerator\Model\AbstractSimpleValue;

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
