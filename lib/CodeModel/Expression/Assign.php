<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Expression;

use Kassko\Test\UnitTestsGenerator\CodeModel\Expression;
use Kassko\Test\UnitTestsGenerator\CodeModel\Parameter;
use Kassko\Test\UnitTestsGenerator\CodeModel\Value;

/**
 * Assign
 */
class Assign extends Expression
{
    /**
     * @var Value
     */
    private $objectValue;
    /**
     * @var Expression
     */
    private $expression;

    /**
     * @param Value         $objectValue
     * @param Expression    $expression
     */
    public function __construct(Value $objectValue, Expression $expression)
    {
        $this->objectValue = $objectValue;
        $this->expression = $expression;
    }

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->expression;
    }
}
