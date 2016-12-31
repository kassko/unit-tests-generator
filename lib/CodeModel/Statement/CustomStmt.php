<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Statement;

use Kassko\Test\UnitTestsGenerator\CodeModel\Expression;
use Kassko\Test\UnitTestsGenerator\CodeModel\Statement;

/**
 * CustomStmt
 */
class CustomStmt implements Statement
{
    /**
     * @var Expression
     */
    private $expression;

    /**
     * @param Expression $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * @param Expression $expression
     *
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->expression;
    }
}
