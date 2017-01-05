<?php

namespace Kassko\Test\UnitTestsGenerator\PlanLoader;

use Doctrine\Common\Annotations\Reader;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;
use Kassko\Test\UnitTestsGenerator\PlanProviderResource;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;

/**
 * AnnotationLoader
 */
class AnnotationLoader extends \Kassko\Test\UnitTestsGenerator\AbstractPlanLoader
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
     * @param Reader        $reader
     * @param Reflector     $reflector
     * @param ArrayLoader   $arrayLoader
     */
    public function __construct(Reader $reader, Reflector $reflector, ArrayLoader $arrayLoader)
    {
        parent::__construct($arrayLoader);

        $this->reader = $reader;
        $this->reflector = $reflector;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PlanProviderResource $providerResource)
    {
        return 'class' === $providerResource->getType();
    }

    /**
     * @param array $annotations
     * @param array $classes
     *
     * @return bool
     */
    protected function containsAtLeastOneAnnotationsOfGivenClasses(array $annotations, array $classes)
    {
        foreach ($annotations as $annotation) {
            if (in_array(get_class($annotation), $classes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(PlanProviderResource $providerResource)
    {
        $data = [];

        $fullClass = $providerResource->getResource();
        $reflClass = $this->reflector->getReflectionClass($fullClass);

        $data['properties'] = [];
        foreach ($reflClass->getProperties() as $reflProperty) {
            $annotations = $this->reader->getPropertyAnnotations($reflProperty);
            if (!$this->containsAtLeastOneAnnotationsOfGivenClasses($annotations, [Ut\Type::class])) {
                continue;
            }

            $propName = $reflProperty->getName();
            $data['properties'][$propName]['config'] = [];
            $data['properties'][$propName]['name'] = $propName;

            foreach ($annotations as $annotation) {
                switch (true) {
                    case $annotation instanceof Ut\Type:
                        $data['properties'][$propName]['config']['type'] = $this->extractType($annotation->type);
                        break;
                }
            }
        }

        foreach ($reflClass->getMethods() as $reflMethod) {
            $annotations = $this->reader->getMethodAnnotations($reflMethod);
            if (
                !$this->containsAtLeastOneAnnotationsOfGivenClasses(
                    $annotations,
                    [Ut\Expectations::class, Ut\MocksStore::class, Ut\SpiesStore::class]
                )
            ) {
                continue;
            }

            $methodName = $reflMethod->getName();
            $data['methods'][$methodName]['config'] = [];
            $data['methods'][$methodName]['name'] = $methodName;

            $hasAnnotations = false;
            foreach ($annotations as $annotation) {
                switch (true) {
                    case $annotation instanceof Ut\Expectations:
                        $data['methods'][$methodName]['config']['expectations'] = $this->extractExpectations($annotation);
                        break;
                    case $annotation instanceof Ut\MocksStore:
                        $data['methods'][$methodName]['config']['mocks_store'] = $this->extractMocksStore($annotation);
                        break;
                    case $annotation instanceof Ut\SpiesStore:
                        $data['methods'][$methodName]['config']['spies_store'] = $this->extractSpiesStore($annotation);
                        break;
                }
            }
        }

        return $data;
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
            'return' => $this->extractReturn($expectation->return),
            'mocks' => $this->extractMocks($expectation->mocks),
            'spies' => $this->extractSpies($expectation->spies),
            'enabled' => $expectation->enabled
        ];
    }

    /**
     * @param mixed $return
     *
     * @return array
     */
    protected function extractReturn($return)
    {
        switch (true) {
            case is_scalar($return):
                return ['type' => 'scalar', 'config' => ['scalar' => ['value' => $return]]];
        }

        return [];
    }

    /**
     * @param mixed $return
     *
     * @return array
     */
    protected function extractScalarReturn($return)
    {
        return ['value' => $return];
    }

    /**
     * @param Ut\Mocks $mocks (default|null)
     *
     * @return array
     */
    protected function extractMocks(Ut\Mocks $mocks = null)
    {
        return $mocks ? $mocks->items['value'] : null;
    }

    /**
     * @param Ut\Spies $spies (default|null)
     *
     * @return array
     */
    protected function extractSpies(Ut\Spies $spies = null)
    {
        return $spies ? $spies->items['value'] : null;
    }

    /**
     * @param Ut\MocksStore $mocksStore
     *
     * @return array
     */
    protected function extractMocksStore(Ut\MocksStore $mocksStore)
    {
        $data = [];

        foreach ($mocksStore->items as $mock) {
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
            'behaviour' => $this->extractMockBehaviour($mock->behav),
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
                return ['type' => 'method', 'config' => ['method' => $this->extractMethodCall($expr)]];
            case $expr instanceof Ut\Expression\OppositeMockOf:
                return ['type' => 'opposite_mock_of', 'config' => ['opposite_mock_of' => ['id' => $expr->id]]];
            case $expr instanceof Ut\Expression\Mocks:
                return ['type' => 'mocks', 'config' => ['mocks' => ['items' => $expr->items]]];
        }

        return [];
    }

    /**
     * @param Ut\Expression\Method $method
     *
     * @return array
     */
    protected function extractMethodCall(Ut\Expression\Method $method)
    {
        return ['obj' => $method->obj, 'member' => $method->member, 'func' => $method->func];
    }

    /**
     * @param Ut\MockBehaviour  $behaviour  (default|null)
     * @param mixed             $returnValue (default|null)
     *
     * @return array
     */
    protected function extractMockBehaviour(Ut\MockBehaviour $behaviour = null, $returnValue = null)
    {
        switch (true) {
            case $behaviour instanceof Ut\MockBehaviour\Noop:
                return ['type' => 'noop'];
            case $behaviour instanceof Ut\MockBehaviour\RetInstanceOf:
                return ['type' => 'return_instance_of', 'config' => ['return_instance_of' => ['class' => $behaviour->class]]];
            case $behaviour instanceof Ut\MockBehaviour\RetVal:
                return ['type' => 'return', 'config' => ['return' => ['value' => $behaviour->val]]];
            case isset($returnValue):
                return ['type' => 'return', 'config' => ['return' => ['value' => $returnValue]]];
        }

        return [];
    }

    /**
     * @param Ut\SpiesStore $spiesStore
     *
     * @return array
     */
    protected function extractSpiesStore(Ut\SpiesStore $spiesStore)
    {
        $data = [];

        foreach ($spiesStore->items as $spy) {
            $data[$spy->id] = $this->extractSpy($spy);
        }

        return $data;
    }

    /**
     * @param Ut\Spy $spy
     *
     * @return array
     */
    protected function extractSpy(Ut\Spy $spy)
    {
        return [
            'id' => $spy->id,
            'expected' => $this->extractSpyKind($spy->expected),
            'enabled' => $spy->enabled
        ];
    }

    /**
     * @param Ut\SpyKind $spyKind
     *
     * @return array
     */
    protected function extractSpyKind(Ut\SpyKind $spyKind)
    {
        switch (true) {
            case $spyKind instanceof Ut\SpyKind\Calls:
                return ['type' => 'calls', 'config' => ['calls' => $this->extractCalls($spyKind)]];
            case $spyKind instanceof Ut\SpyKind\Exception_:
                return ['type' => 'exception', 'config' => ['exception' => $this->extractException($spyKind)]];
        }
    }

    /**
     * @param Ut\Exception_ $exception
     *
     * @return array
     */
    protected function extractCalls(Ut\SpyKind\Calls $calls)
    {
        return ['nr' => $calls->nr, 'method' => $this->extractMethodCall($calls->method)];
    }

    /**
     * @param Ut\Exception_ $exception
     *
     * @return array
     */
    protected function extractException(Ut\SpyKind\Exception_ $exception)
    {
        return ['class' => $exception->class, 'code' => $exception->code, 'message' => $exception->message];
    }

    /**
     * @param Ut\Type $type
     *
     * @return array
     */
    protected function extractType(Ut\Type $type)
    {
        return ['value' => $type->value];
    }
}
