<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

/**
 * Mock
 */
class Mock
{
    use ActivableTrait;

    /**
     * @var string
     */
    private $id;
    /**
     * @var Expression
     */
    private $expression;
    /**
     * @var Behaviour
     */
    private $behaviour;

    /**
     * @param string        $id
     * @param Expression    $expression
     * @param Behaviour     $behaviour
     * @param bool          $activated (default)
     */
    public function __construct($id, Expression $expression, Behaviour $behaviour, $activated = true)
    {
        parent::__construct($id, $activated);

        $this->expression = $expression;
        $this->behaviour = $behaviour;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Expression $expression
     *
     * @return $this
     */
    public function setExpression(Expression $expression)
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

    /**
     * @param Behaviour $behaviour
     *
     * @return $this
     */
    public function setBehaviour(Behaviour $behaviour)
    {
        $this->behaviour = $behaviour;

        return $this;
    }

    /**
     * @return Behaviour
     */
    public function getBehaviour()
    {
        return $this->behaviour;
    }
}
