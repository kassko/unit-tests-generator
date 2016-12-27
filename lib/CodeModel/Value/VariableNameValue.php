<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Value;

use Kassko\Test\UnitTestsGenerator\CodeModel\AbstractSimpleValue;

/**
 * VariableNameValue
 */
class VariableNameValue extends AbstractSimpleValue
{
    /**
     * @{inheritdoc}
     */
    public function getAsScalar()
    {
        return sprintf('$%s', $this->value);
    }
}
