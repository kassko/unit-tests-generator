<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

class MocksStore
{
    /**
     * @var Mock[]
     */
    private $mocks;

    /**
     * @param Mock[] $mocks
     */
    public function __construct(array $mocks)
    {
        $this->mocks = $mocks;
    }

    /**
     * @param string $id
     * @param Mock $mock
     *
     * @return $this
     */
    public function addMock($id, Mock $mock)
    {
        if (isset($this->mocks[$id])) {
            throw new \DomainException(sprintf('Cannot add a mock with the id "%s" in the mocks store because a mock with this id already exists.', $id));
        }

        $this->mocks[$id] = $mock;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function removeMock($id)
    {
        if (!isset($this->mocks[$id])) {
            throw new \DomainException(sprintf('Cannot remove the mock with id "%s" from the mocks store because there is not mock with such id.', $id));
        }
        unset($this->mocks[$id]);

        return $this;
    }

    /**
     * @param Mock[]
     *
     * @return $this
     */
    public function setMocks(array $mocks)
    {
        $this->mocks = $mocks;

        return $this;
    }

    /**
     * @return Mock[]
     */
    public function getMocks()
    {
        return $this->mocks;
    }
}
