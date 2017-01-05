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

        return $classPlanModel;
    }
}
