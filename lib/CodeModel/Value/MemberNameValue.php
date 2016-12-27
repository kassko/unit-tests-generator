<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Value;

use Kassko\Test\UnitTestsGenerator\CodeModel\AbstractSimpleValue;

/**
 * MemberNameValue
 */
class MemberNameValue extends AbstractSimpleValue
{
    /**
     * @{inheritdoc}
     */
    public function getAsScalar()
    {
        return sprintf('$this->%s', $this->value);
    }
}
