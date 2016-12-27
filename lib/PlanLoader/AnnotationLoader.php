<?php

namespace Kassko\Test\UnitTestsGenerator\PlanLoader;

use Doctrine\Common\Annotations\Reader;
use Kassko\Test\UnitTestsGenerator\PlanLoader;
use Kassko\Test\UnitTestsGenerator\PlanModel;
use Kassko\Test\UnitTestsGenerator\PlanProviderResource;

/**
 * AnnotationLoader
 */
class AnnotationLoader implements PlanLoader
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var PlanArrayLoader
     */
    private $planArrayLoader;

    /**
     * @param Reader            $reader
     * @param ArrayPlanLoader   $arrayPlanLoader
     */
    public function __construct(Reader $reader, ArrayPlanLoader $arrayPlanLoader)
    {
        $this->reader = $reader;
        $this->arrayPlanLoader = $arrayPlanLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PlanProviderResource $providerResource)
    {
        return 'class' === $providerResource->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function load(PlanModel\Class_ $classPlanModel, PlanProviderResource $providerResource)
    {
        //Path to the file
        $filePath = $providerResource->getResource();

        $data = [];//todo: feed $data

        return $this->arrayPlanLoader->load($classPlanModel, new PlanProviderResource('array', $data));
    }
}
