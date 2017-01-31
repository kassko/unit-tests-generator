<?php

namespace Kassko\Test\UnitTestsGenerator\PlanLoader;

use Kassko\Test\UnitTestsGenerator\PlanModel;
use Kassko\Test\UnitTestsGenerator\PlanProviderResource;

/**
 * ArrayLoader
 */
class ArrayLoader implements \Kassko\Test\UnitTestsGenerator\PlanLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports(PlanProviderResource $providerResource)
    {
        return 'array' === $providerResource->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function load(PlanModel\Class_ $classPlanModel, PlanProviderResource $providerResource)
    {
        $data = $providerResource->getResource();

        /** Here load plan model from data */

        $properties = $this->createProperties($data['properties']);
        $classPlanModel->setProperties($properties);

        //Methods



        return $classPlanModel;
    }

    protected function createProperties(array $data)
    {
        $properties = [];

        foreach ($data as $name => $item) {
            if (false === $item['config']['activated']) {
                continue;
            }

            if ($item['config']['type']['activated']) {
                $property = new PlanModel\Property($name, $item['config']['type']['value']);
            }

            $properties[$name] = $property;
        }

        return $properties;
    }

    protected function createMethods(array $data)
    {
        $methods = [];

        foreach ($data as $name => $item) {
            if (false === $item['config']['activated']) {
                continue;
            }

            $methods[$name] = $this->createMethod($item['name'], $item['config']);
        }

        return $methods;
    }

    protected function createMethod($name, array $data)
    {
        $method = new PlanModel\Method($name);

        if (isset($data['expectations'])) {
            $method->setExpectations($this->createExpectations($data['expectations']));
        }

        if (isset($data['mocks_store'])) {
            $method->setMocksStore($this->createMocksStore($data['mocks_store']));
        }

        if (isset($data['spies_store'])) {
            $method->setSpiesStore($this->createSpiesStore($data['spies_store']));
        }

        return $method;
    }

    protected function createExpectations(array $data)
    {
        $expectations = [];

        foreach ($data as $id => $item) {
            $expectations[$id] = $this->createExpectation($item);
        }

        return $expectations;
    }

    protected function createMocksStore(array $data)
    {
        $mocksStore = [];

        foreach ($data as $id => $item) {
            $mocksStore[$id] = $this->createMock($item);
        }

        return $mocksStore;
    }

    protected function createMock(array $data)
    {
        $mock = new Mock($data[$id]);

        //if ($data['type'] === '')

        return $mock;
    }

    protected function createSpiesStore(array $data)
    {
        $spiesStore = [];

        foreach ($data as $id => $item) {
            $spiesStore[$id] = $this->createSpy($item);
        }

        return $spiesStore;
    }
}
