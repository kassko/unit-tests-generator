<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Value;

use Kassko\Test\UnitTestsGenerator\Model\AbstractSimpleValue;

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
