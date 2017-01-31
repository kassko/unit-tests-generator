<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\Expectation;
use Kassko\Test\UnitTestsGenerator\PlanModel\ActivableTrait;

/**
 * Method
 */
class Method
{
    use ActivableTrait;

    /**
     * @var string
     */
    private $name;
    /**
     * @var Expectation[]
     */
    private $expectations = [];
    /**
     * @var MocksStore[]
     */
    private $mocksStore = [];
    /**
     * @var SpiesStore[]
     */
    private $spiesStore = [];

    /**
     * @param string        $name
     * @param Expectation[] $expectations (default)
     * @param MocksStore[]  $mocksStore (default)
     * @param SpiesStore[]  $spiesStore (default)
     */
    public function __construct($name, array $expectations = [], array $mocksStore = [], array $spiesStore = [])
    {
        $this->name = $name;
        $this->expectations = $expectations;
        $this->mocksStore = $mocksStore;
        $this->spiesStore = $spiesStore;
    }

    /**
     * @param string $id
     * @param Expectation $expectation
     *
     * @return $this
     */
    public function addExpectation($id, Expectation $expectation)
    {
        if (isset($this->expectations[$id])) {
            throw new \DomainException(sprintf('Cannot add an expectation with the id "%s". An expectation with this id already exists.', $id));
        }

        $this->expectations[$id] = $expectation;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function removeExpectation($id)
    {
        if (!isset($this->expectations[$id])) {
            throw new \DomainException(sprintf('Cannot remove the expectation with id "%s". There is not expectation with such id.', $id));
        }
        unset($this->expectations[$id]);

        return $this;
    }

    /**
     * @param Expectations[] $expectations
     *
     * @return $this
     */
    public function setExpectations(array $expectations)
    {
        $this->expectations = $expectations;

        return $this;
    }

    /**
     * @return Expectation[]
     */
    public function getExpectations()
    {
        return $this->expectations;
    }

    /**
     * @return MocksStore
     */
    public function getMocksStore()
    {
        return $this->mocksStore;
    }

    /**
     * @param MocksStore $mocksStore
     *
     * @return $this
     */
    public function setMocksStore(MocksStore $mocksStore)
    {
        $this->mocksStore = $mocksStore;

        return $this;
    }

    /**
     * @return SpiesStore
     */
    public function getSpiesStore()
    {
        return $this->spiesStore;
    }

    /**
     * @param SpiesStore $spiesStore
     *
     * @return $this
     */
    public function setSpiesStore(SpiesStore $spiesStore)
    {
        $this->spiesStore = $spiesStore;

        return $this;
    }
}
