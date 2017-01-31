<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

class SpiesStore
{
    /**
     * @var Spy[]
     */
    private $spies;

    /**
     * @param Spy[] $spies
     */
    public function __construct(array $spies)
    {
        $this->spies = $spies;
    }

    /**
     * @param string $id
     * @param Spy $spy
     *
     * @return $this
     */
    public function addSpy($id, Spy $spy)
    {
        if (isset($this->spies[$id])) {
            throw new \DomainException(sprintf('Cannot add a spy with the id "%s" in the spies store because a spy with this id already exists.', $id));
        }

        $this->spies[$id] = $spy;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function removeSpy($id)
    {
        if (!isset($this->spies[$id])) {
            throw new \DomainException(sprintf('Cannot remove the spy with id "%s" from the spies store because there is not spy with such id.', $id));
        }
        unset($this->spies[$id]);

        return $this;
    }

    /**
     * @param Spy[]
     *
     * @return $this
     */
    public function setSpies(array $spies)
    {
        $this->spies = $spies;

        return $this;
    }

    /**
     * @return Spy[]
     */
    public function getSpies()
    {
        return $this->spies;
    }
}
