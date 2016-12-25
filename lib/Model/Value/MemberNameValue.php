<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Value;

use Kassko\Test\UnitTestsGenerator\Model\AbstractSimpleValue;

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
