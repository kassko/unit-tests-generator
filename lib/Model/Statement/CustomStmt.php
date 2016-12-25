<?php

namespace Kassko\Test\UnitTestsGenerator\Model\Statement;

use Kassko\Test\UnitTestsGenerator\Model\Expression;
use Kassko\Test\UnitTestsGenerator\Model\Statement;

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
     * @return self
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
