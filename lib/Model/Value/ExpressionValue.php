<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Value;

use Kassko\Test\UnitTestsGenerator\Model\AbstractValue;
use Kassko\Test\UnitTestsGenerator\Model\Expression;

/**
 * ExpressionValue
 */
class ExpressionValue extends AbstractValue
{
    /**
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        parent::__construct($expression);
    }
}
