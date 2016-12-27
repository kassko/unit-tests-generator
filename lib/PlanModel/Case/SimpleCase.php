<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Case;

use Kassko\Test\UnitTestsGenerator\PlanModel\AbstractCase;

/**
 * SimpleCase
 */
class SimpleCase extends AbstractCase
{
    /**
     * @var Expression
     */
    private $expression;
    /**
     * @var Value
     */
    private $value;

    /**
     * @param string        $id
     * @param Expression    $expression
     * @param Value         $value
     * @param bool          $enabled (default)
     */
    public function __construct($id, Expression $expression, Value $value, $enabled = true)
    {
        parent::__construct($id, $enabled);

        $this->expression = $expression; 
        $this->value = $value;
    }   

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return Value
     */
    public function getValue()
    {
        return $this->value;
    }
}
