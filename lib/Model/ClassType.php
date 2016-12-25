<?php

namespace Kassko\Test\UnitTestsGenerator\Model;

use Kassko\Test\UnitTestsGenerator\Model\InitStatementAwareTrait;
use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\Model\Method;
use Kassko\Test\UnitTestsGenerator\Visitor\NamespacesCollectorVisitor;
use LogicException;

/**
 * ClassType
 */
class ClassType
{
    use InitStatementAwareTrait;

    /**
     * @var string
     */
    private $fullClass;
    /**
     * @var array
     */
    private $usedNamespaces = [];
    /**
     * @var Method[]
     */
    private $methods = [];
    /**
     * @var Method[]
     */
    private $getters = [];
    /**
     * @var Method[]
     */
    private $setters = [];

    /**
     * @param string $fullClass
     */
    public function __construct($fullClass)
    {
        $this->fullClass = $fullClass;
    }

    public function accept(NamespacesCollectorVisitor $nsCollectorVisitor)
    {
        $nsCollectorVisitor->visit($this);
    }

    /**
     * @param array $namespaces
     *
     * @return self
     */
    public function useNamespaces(array $namespaces)
    {
        $this->usedNamespaces = $namespaces;

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return self
     */
    public function useNamespace($namespace)
    {
        $this->usedNamespaces[$namespace] = $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getUsedNamespaces()
    {
        return $this->usedNamespaces;
    }

    /**
     * @return string
     */
    public function getFullClass()
    {
        return $this->fullClass;
    }

    /**
     * @param string $methodName
     *
     * @return self
     */
    public function createMethod($methodName)
    {
        return new Method($methodName, $this);
    }

    /**
     * @param string $methodName
     * @param Method $method
     *
     * @return self
     */
    public function addMethod($methodName, Method $method)
    {
        $this->methods[$methodName] = $method;

        return $this;
    }

    /**
     * @param string $methodName
     *
     * @return Method
     */
    public function getMethod($methodName)
    {
        if (!isset($this->methods[$methodName])) {
            throw new LogicException(sprintf('The method "%s::%s" does not exist in model.', $this->fullClass, $methodName));
        }

        return $this->methods[$methodName];
    }

    /**
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
