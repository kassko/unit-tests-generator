<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Value;

use Kassko\Test\UnitTestsGenerator\CodeModel\AbstractValue;
use Kassko\Test\UnitTestsGenerator\CodeModel\Expression;

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
