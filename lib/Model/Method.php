<?php

namespace Kassko\Test\UnitTestsGenerator\Model;

use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\Model\Statement\Assert;
use Kassko\Test\UnitTestsGenerator\Model\InitStatementAwareTrait;
use Kassko\Test\UnitTestsGenerator\Model\Statement\CustomStmt;
use Kassko\Test\UnitTestsGenerator\Model\Statement\Spy;

/**
 * Method
 */
class Method
{
    use InitStatementAwareTrait;

    /**
     * @var string
     */
    private $name;
    /**
     * @var ClassType
     */
    private $classType;
    /**
     * @var Spy[]
     */
    private $spies = [];
    /**
     * @var CustomStmt[]
     */
    private $customStmts = [];
    /**
     * @var AbstractAssert[]
     */
    private $asserts = [];

    /**
     * @param string    $name
     * @param ClassType $classType
     */
    public function __construct($name, ClassType $classType)
    {
        $this->name = $name;
        $this->classType = $classType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ClassType
     */
    public function getClassType()
    {
        return $this->classType;
    }

    /**
     * @param Spy
     *
     * @return self
     */
    public function addSpy(Spy $spy)
    {
        $this->spies[] = $spy;

        return $this;
    }

    /**
     * @return Spy[]
     */
    public function getSpies()
    {
        return $this->spies;
    }

    /**
     * @param CustomStmt $customStmt
     *
     * @return self
     */
    public function addCustomStmt(CustomStmt $customStmt)
    {
        $this->customStmts[] = $customStmt;

        return $this;
    }

    /**
     * @return CustomStmt[]
     */
    public function getCustomStmts()
    {
        return $this->customStmts;
    }

    /**
     * @param Assert $assert
     *
     * @return self
     */
    public function addAssert(Assert $assert)
    {
        $this->asserts[] = $assert;

        return $this;
    }

    /**
     * @return Assert[]
     */
    public function getAsserts()
    {
        return $this->asserts;
    }
}
