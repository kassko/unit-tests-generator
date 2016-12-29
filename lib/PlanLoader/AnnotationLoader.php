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
     * @var ArrayLoader
     */
    private $arrayLoader;

    /**
     * @param Reader        $reader
     * @param Reflector     $reflector
     * @param ArrayLoader   $arrayLoader
     */
    public function __construct(Reader $reader, Reflector $reflector, ArrayLoader $arrayLoader)
    {
        $this->reader = $reader;
        $this->reflector = $reflector;
        $this->arrayLoader = $arrayLoader;
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
                    case $annotation instanceof Ut\MocksStore:
                        $data['methods'][$methodName] = ['mocks_store' => $this->extractMocksStore($annotation)];
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
                        $data['props'][$propName] = ['type' => $this->extractType($annotation->type)];
                        break;
                }
            }
        }

        return $this->arrayLoader->load($classPlanModel, new PlanProviderResource('array', $data));
    }

    /**
     * @param Ut\Expectations $expectations
     *
     * @return array
     */
    protected function extractExpectations(Ut\Expectations $expectations)
    {
        $data = [];

        foreach ($expectations->items as $expectation) {
            $data[] = $this->extractExpectation($expectation);
        }

        return $data;
    }

    /**
     * @param Ut\Expectation $expectation
     *
     * @return array
     */
    protected function extractExpectation(Ut\Expectation $expectation)
    {
        return [
            'expected' => $this->extractExpected($path->expected), 
            'path' => $this->extractPath($expectation->path),
            'enabled' => $expectation->enabled
        ];
    }

    /**
     * @param mixed $expected
     *
     * @return array
     */
    protected function extractExpected($expected)
    {
        switch (true) {
            case is_scalar($expected):
                return ['type' => 'scalar', 'config' => $expected];
            case $expected instanceof Ut\Exception:
                return ['type' => 'exception', 'config' => $this->extractException($expected)];
        }

        return [];
    }

    /**
     * @param Ut\Path $path
     *
     * @return array
     */
    protected function extractPath(Ut\Path $path)
    {
        return ['mocks' => $path->mocks];
    }

    /**
     * @param Ut\MocksStore $casesStore
     *
     * @return array
     */
    protected function extractMocksStore(Ut\MocksStore $casesStore)
    {
        $data = [];

        foreach ($casesStore->items as $mock) {
            $data[$mock->id] = $this->extractMock($mock);
        }

        return $data;
    }

    /**
     * @param Ut\Mock $mock
     *
     * @return array
     */
    protected function extractMock(Ut\Mock $mock)
    {
        return [
            'id' => $mock->id, 
            'expr' => $this->extractExpression($mock->expr), 
            'mock_behaviour' => $this->extractMockBehaviour($mock->behav),
            'enabled' => $mock->enabled
        ];
    }

    /**
     * @param Ut\Expression $expr
     *
     * @return array
     */
    protected function extractExpression(Ut\Expression $expr)
    {
        switch (true) {
            case $expr instanceof Ut\Expression\Method:
                return ['type' => 'method', 'config' => ['obj' => $expr->obj, 'func' => $expr->func, 'member' => $expr->member]];
            case $expr instanceof Ut\Expression\OppositeMockOf:
                return ['type' => 'opposite_mock_of', 'config' => ['id' => $expr->id]];
            case $expr instanceof Ut\Expression\Mocks:
                return ['type' => 'mocks', 'config' => ['items' => $expr->items]];
        }

        return [];
    }

    /**
     * @param Ut\MockBehaviour  $behaviour
     * @param mixed             $returnValue
     *
     * @return array
     */
    protected function extractMockBehaviour(Ut\MockBehaviour $behaviour = null, $returnValue = null)
    {
        switch (true) {
            case $behaviour instanceof Ut\MockBehaviour\Noop:
                return ['type' => 'noop'];
            case $behaviour instanceof Ut\MockBehaviour\RetInstanceOf:
                return ['type' => 'ret_instance_of', 'config' => ['full_class' => $behaviour->fullClass]];
            case $behaviour instanceof Ut\MockBehaviour\RetVal:
                return ['type' => 'ret_val', 'config' => ['val' => $behaviour->val]];
            case isset($returnValue):
                return ['type' => 'ret_val', 'config' => ['val' => $returnValue]];
        }

        return [];
    }

    /**
     * @param Ut\Exception_ $exception
     *
     * @return array
     */
    protected function extractException(Ut\Exception_ $exception)
    {
        return ['full_class' => $exception->fullClass, 'code' => $exception->code, 'msg' => $exception->msg];
    }

    /**
     * @param Ut\Type $type
     *
     * @return array
     */
    protected function extractType(Ut\Type $type)
    {
        return ['type' => $type->val];
    }
}
