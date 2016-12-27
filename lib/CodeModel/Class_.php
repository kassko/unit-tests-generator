<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel;

use Kassko\Test\UnitTestsGenerator\CodeModel\InitStatementAwareTrait;
use Kassko\Test\UnitTestsGenerator\CodeModel\Class_;
use Kassko\Test\UnitTestsGenerator\CodeModel\Method;
use Kassko\Test\UnitTestsGenerator\NamespacesCollectorVisitor;
use LogicException;

/**
 * Class_
 */
class Class_
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
     * @param Method $method
     *
     * @return self
     */
    public function addMethod(Method $method)
    {
        $this->methods[$method->getName()] = $method;

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
