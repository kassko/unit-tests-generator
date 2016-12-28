<?php

namespace Kassko\Test\UnitTestsGenerator\PlanLoader;

use Doctrine\Common\Annotations\Reader;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;
use Kassko\Test\UnitTestsGenerator\PlanLoader;
use Kassko\Test\UnitTestsGenerator\PlanModel;
use Kassko\Test\UnitTestsGenerator\PlanProviderResource;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;

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
     * @var Reflector
     */
    private $reflector;
    /**
     * @var ArrayPlanLoader
     */
    private $arrayPlanLoader;

    /**
     * @param Reader            $reader
     * @param Reflector         $reflector
     * @param ArrayPlanLoader   $arrayPlanLoader
     */
    public function __construct(Reader $reader, Reflector $reflector, ArrayPlanLoader $arrayPlanLoader)
    {
        $this->reader = $reader;
        $this->reflector = $reflector;
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
        $data = [];

        $fullClass = $providerResource->getResource();
        $reflClass = $this->reflector->getReflectionClass($fullClass);
        foreach ($reflClass->getMethods() as $reflMethod) {
            $methodName = $reflMethod->getName();

            $annotations = $this->reader->getMethodAnnotations($reflMethod);
            foreach ($annotations as $annotation) {
                switch (true) {
                    case $annotation instanceof Ut\CasesStore:
                        $data['methods'][$methodName] = [];
                        $data['methods'][$methodName]['cases_store'] = $this->extractCasesStore($annotation);
                        break;
                }
            }
        }

        $data['props'] = [];
        foreach ($reflClass->getProperties() as $reflProperty) {
            $propName = $reflProperty->getName();

            $annotations = $this->reader->getPropertyAnnotations($reflProperty);
            foreach ($annotations as $annotation) {
                switch (true) {
                    case $annotation instanceof Ut\Type:
                        $data['props'][$propName] = [];
                        $data['props'][$propName]['type'] = extractType($annotation->type);
                        break;
                }
            }
        }

        return $this->arrayPlanLoader->load($classPlanModel, new PlanProviderResource('array', $data));
    }

    /**
     * @param Ut\CasesStore $casesStore
     *
     * @return array
     */
    protected function extractCasesStore(Ut\CasesStore $casesStore)
    {
        $data = [];

        foreach ($casesStore->items as $case) {
            $data[$case->id] = $this->extractCase($case);
        }

        return $data;
    }

    /**
     * @param Ut\Case_ $case
     *
     * @return array
     */
    protected function extractCase(Ut\Case_ $case)
    {
        return ['id' => $case->id, 'expr' => $this->extractExpression($case->expr), 'value' => $case->value];
    }

    /**
     * @param Ut\Expression $expr
     *
     * @return array
     */
    protected function extractExpression(Ut\Expression $expr)
    {
        $data = [];

        switch (true) {
            case $expr instanceof Ut\Expression\Method:
                $data['method'] = ['obj' => $expr->obj, 'func' => $expr->func, 'member' => $expr->member];
                break;
            case $expr instanceof Ut\Expression\NotCase:
                $data['not_case'] = ['id' => $expr->id];
                break;
        }

        return $data;
    }

    /**
     * @param Ut\Type $type
     *
     * @return array
     */
    protected function extractType(Ut\Type $type)
    {
        return ['type' => $type->type];
    }
}
