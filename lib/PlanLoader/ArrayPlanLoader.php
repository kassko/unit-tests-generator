<?php

namespace Kassko\Test\UnitTestsGenerator\PlanLoader;

use Kassko\Test\UnitTestsGenerator\PlanLoader;
use Kassko\Test\UnitTestsGenerator\PlanModel;
use Kassko\Test\UnitTestsGenerator\PlanProviderResource;

/**
 * ArrayPlanLoader
 */
class ArrayPlanLoader implements PlanLoader
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
        //array data
        //$data = $providerResource->getResource();

        return $classPlanModel;
    }
}
