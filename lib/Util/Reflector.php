<?php

namespace Kassko\Test\UnitTestsGenerator\Util;

use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Reflector
 */
class Reflector
{
    /**
     * @var array
     */
    private $classes = [];
    /**
     * @var ReflectionClass[]
     */
    private $reflectionClasses = [];
    /**
     * @var ClassNameParser
     */
    protected $classNameParser;

    /**
     * @param ClassNameParser $classNameParser
     */
    public function __construct(ClassNameParser $classNameParser)
    {
        $this->classNameParser = $classNameParser;
    }

    /**
     * @param string $fullClass
     *
     * @return ReflectionClass[]
     */
    public function getReflectionClass($fullClass)
    {
        if (! isset($this->reflectionClasses[$fullClass])) {
            $this->reflectionClasses[$fullClass] = new ReflectionClass($fullClass);
        }

        return $this->reflectionClasses[$fullClass];
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    public function getGetters($fullClass)
    {
        if (! isset($this->classes[$fullClass]['getters'])) {

            $properties = $this->getPropertiesNames($fullClass);
            $methods = $this->getMethodsNames($fullClass);

            $getters = array_filter(
                $methods,
                function ($method) use ($properties) {
                    return
                    'get' === substr($method, 0, 3)
                    &&
                    in_array(lcfirst(substr($method, 3)), $properties);
                }
            );

            $isers = array_filter(
                $methods,
                function ($method) use ($properties) {
                    return 'is' === substr($method, 0, 2)
                    &&
                    in_array(lcfirst(substr($method, 2)), $properties);
                }
            );

            $hasers = array_filter(
                $methods,
                function ($method) use ($properties) {
                    return
                    'has' === substr($method, 0, 3)
                    &&
                    in_array(lcfirst(substr($method, 3)), $properties);
                }
            );

            $this->classes[$fullClass]['getters'] = array_merge($getters, $isers, $hasers);
        }

        return $this->classes[$fullClass]['getters'];
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    public function getSetters($fullClass)
    {
        if (! isset($this->classes[$fullClass]['setters'])) {

            $properties = $this->getPropertiesNames($fullClass);
            $methods = $this->getMethodsNames($fullClass);

            $this->classes[$fullClass]['setters'] = array_filter(
                $methods,
                function ($method) use ($properties) {
                    return
                    'set' === substr($method, 0, 3)
                    &&
                    in_array(lcfirst(substr($method, 3)), $properties);
                }
            );
        }

        return $this->classes[$fullClass]['setters'];
    }

    /**
     * @param string $fullClass
     * @param string $property
     *
     * @return array
     */
    public function getPropertyType($fullClass, $property)
    {
        return $this->getPropertyDocComment($fullClass, $property, '/@var\s+([^\s]+)/', 'type');
    }

    /**
     * @param string $fullClass
     * @param string $method
     *
     * @return array
     */
    public function getMethodReturnType($fullClass, $method)
    {
        return $this->getFirstMethodDocComment($fullClass, $method, '/@return\s+([^\s]+)/', 'return_type');
    }

    /**
     * @param string $fullClass
     * @param string $method
     *
     * @return array
     */
    public function getMethodParams($fullClass, $method)
    {
        return $this->getMethodDocComment($fullClass, $method, '/@param\s+([^\s]+)\s+([^\s]+)/', 'params');
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    public function getConstructorParams($fullClass)
    {
        return $this->getMethodDocComment($fullClass, '__construct', '/@param\s+([^\s]+)\s+([^\s]+)/', 'params');
    }

    /**
     * @param string $fullClass
     *
     * @return bool
     */
    public function hasConstructor($fullClass)
    {
        return $this->getReflectionClass($fullClass)->hasMethod('__construct');
    }

    /**
     * @param string $fullClass
     * @param string $property
     * @param string $pattern
     * @param string $tag
     *
     * @return array
     */
    protected function getPropertyDocComment($fullClass, $property, $pattern, $tag)
    {
        $properties = &$this->getProperties($fullClass);

        if (! isset($properties['struct'][$property][$tag])) {
            $reflProperty = $properties['struct'][$property]['refl'];

            if (preg_match($pattern, $reflProperty->getDocComment(), $matches)) {
                $namespace = $this->getReflectionClass($fullClass)->getNamespaceName();
                list($type, $paramFullClass) = $this->resolveType($matches[1], $namespace, $fullClass);
                $properties['struct'][$property][$tag] = ['type' => $type, 'full_class' => $paramFullClass];
            }
        }

        return $properties['struct'][$property][$tag];
    }

    /**
     * @param string $fullClass
     *
     * @return string
     */
    protected function getPropertiesNames($fullClass)
    {
        return $this->getProperties($fullClass)['meta']['names'];
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    protected function &getProperties($fullClass)
    {
        if (! isset($this->classes[$fullClass]['properties'])) {
            $refl = $this->getReflectionClass($fullClass);

            $noStaticFlag = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;

            $this->classes[$fullClass]['properties'] = ['struct' => [], 'meta' => ['names' => []]];
            foreach ($refl->getProperties($noStaticFlag) as $reflProperty) {
                $this->classes[$fullClass]['properties']['struct'][$reflProperty->getName()] = [
                    'refl' => $reflProperty, 'name' => $reflProperty->getName(), 'type' => null
                ];
                $this->classes[$fullClass]['properties']['meta']['names'][] = $reflProperty->getName();
            }
        }

        return $this->classes[$fullClass]['properties'];
    }

    /**
     * @param string $type
     * @param string $parentNamespace
     * @param string $parentFullClass
     *
     * @return string
     */
    protected function resolveType($type, $parentNamespace, $parentFullClass)
    {
        if ('self' === $type || '$this' === $type) {
            $type = 'object';
            $fullClass = $parentFullClass;
        } elseif (in_array($type, ['boolean', 'float', 'integer', 'string', 'array'])) {
            $fullClass = null;
        } else {
            $fullClass = empty($parentNamespace) ? $type : $this->classNameParser->join($parentNamespace, $type);
            $type = 'object';
        }

        return [$type, $fullClass];
    }

    /**
     * @param string $fullClass
     * @param string $method
     * @param string $pattern
     * @param string $tag
     *
     * @return array
     */
    protected function getFirstMethodDocComment($fullClass, $method, $pattern, $tag)
    {
        $methods = &$this->getMethods($fullClass);

        if (! isset($methods['struct'][$method])) {
            return null;
        }

        if (! isset($methods['struct'][$method][$tag])) {
            $reflMethod = $methods['struct'][$method]['refl'];

            if (preg_match($pattern, $reflMethod->getDocComment(), $matches)) {
                if (count($matches) > 1) {
                    if ('return_type' === $tag) {
                        $namespace = $this->getReflectionClass($fullClass)->getNamespaceName();
                        list($type, $paramFullClass) = $this->resolveType($matches[1], $namespace, $fullClass);
                        $methods['struct'][$method][$tag] = ['type' => $type, 'full_class' => $paramFullClass];
                    }
                }
            }
        }

        return !empty($methods['struct'][$method][$tag]) ? $methods['struct'][$method][$tag] : null;
    }

    /**
     * @param string $fullClass
     * @param string $method
     * @param string $pattern
     * @param string $tag
     *
     * @return array
     */
    protected function getMethodDocComment($fullClass, $method, $pattern, $tag)
    {
        $methods = &$this->getMethods($fullClass);

        if (! isset($methods['struct'][$method])) {
            return null;
        }

        if (! isset($methods['struct'][$method][$tag])) {
            $reflMethod = $methods['struct'][$method]['refl'];

            if (preg_match_all($pattern, $reflMethod->getDocComment(), $matches)) {
                if (count($matches) > 1) {
                    if ('params' === $tag) {
                        $namespace = $this->getReflectionClass($fullClass)->getNamespaceName();
                        foreach ($matches[1] as $index => $match) {
                            list($type, $paramFullClass) = $this->resolveType($match, $namespace, $fullClass);
                            $methods['struct'][$method][$tag][$index] = ['type' => $type, 'full_class' => $paramFullClass];
                        }

                        foreach ($matches[2] as $index => $match) {
                            $methods['struct'][$method][$tag][$index] += ['name' => substr($match, 1)];
                        }
                    }
                }
            }
        }

        return !empty($methods['struct'][$method][$tag]) ? $methods['struct'][$method][$tag] : null;
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    protected function getMethodsNames($fullClass)
    {
        return $this->getMethods($fullClass)['meta']['names'];
    }

    /**
     * @param string $fullClass
     *
     * @return array
     */
    protected function &getMethods($fullClass)
    {
        if (! isset($this->classes[$fullClass]['methods']['meta']['names'])) {
            $refl = $this->getReflectionClass($fullClass);

            $noStaticFlag = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;

            $this->classes[$fullClass]['methods']['meta']['names'] = [];
            $this->classes[$fullClass]['methods']['struct'] = [];
            foreach ($refl->getMethods($noStaticFlag) as $reflMethod) {
                $this->classes[$fullClass]['methods']['struct'][$reflMethod->getName()] = [
                    'refl' => $reflMethod,
                    'name' => $reflMethod->getName(),
                    'return_type' => null,
                    'args' => null
                ];
                $this->classes[$fullClass]['methods']['meta']['names'][] = $reflMethod->getName();
            }
        }

        return $this->classes[$fullClass]['methods'];
    }
}
