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
     * @param string $class
     *
     * @return ReflectionClass[]
     */
    public function getReflectionClass($class)
    {
        if (! isset($this->reflectionClasses[$class])) {
            $this->reflectionClasses[$class] = new ReflectionClass($class);
        }

        return $this->reflectionClasses[$class];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function getGetters($class)
    {
        if (! isset($this->classes[$class]['getters'])) {

            $properties = $this->getPropertiesNames($class);
            $methods = $this->getMethodsNames($class);

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

            $this->classes[$class]['getters'] = array_merge($getters, $isers, $hasers);
        }

        return $this->classes[$class]['getters'];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function getSetters($class)
    {
        if (! isset($this->classes[$class]['setters'])) {

            $properties = $this->getPropertiesNames($class);
            $methods = $this->getMethodsNames($class);

            $this->classes[$class]['setters'] = array_filter(
                $methods,
                function ($method) use ($properties) {
                    return
                    'set' === substr($method, 0, 3)
                    &&
                    in_array(lcfirst(substr($method, 3)), $properties);
                }
            );
        }

        return $this->classes[$class]['setters'];
    }

    /**
     * @param string $class
     * @param string $property
     *
     * @return string
     */
    public function getPropertyType($class, $property)
    {
        return $this->getPropertyDocComment($class, $property, '/@var\s+([^\s]+)/', 'type');
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return string
     */
    public function getMethodReturnValue($class, $method)
    {
        return $this->getFirstMethodDocComment($class, $method, '/@return\s+([^\s]+)/', 'return_value');
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return string
     */
    public function getMethodParams($class, $method)
    {
        return $this->getMethodDocComment($class, $method, '/@param\s+([^\s]+)\s+([^\s]+)/', 'params');
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function getConstructorParams($class)
    {
        return $this->getMethodDocComment($class, '__construct', '/@param\s+([^\s]+)\s+([^\s]+)/', 'params');
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasConstructor($class)
    {
        return $this->getReflectionClass($class)->hasMethod('__construct');
    }

    /**
     * @param string $class
     * @param string $property
     * @param string $pattern
     * @param string $tag
     *
     * @return string
     */
    protected function getPropertyDocComment($class, $property, $pattern, $tag)
    {
        $properties = &$this->getProperties($class);

        if (! isset($properties['struct'][$property][$tag])) {
            $reflProperty = $properties['struct'][$property]['refl'];

            if (preg_match($pattern, $reflProperty->getDocComment(), $matches)) {
                $properties['struct'][$property][$tag] = $matches[1];
            }
        }

        return $properties['struct'][$property][$tag];
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function getPropertiesNames($class)
    {
        return $this->getProperties($class)['meta']['names'];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    protected function &getProperties($class)
    {
        if (! isset($this->classes[$class]['properties'])) {
            $refl = $this->getReflectionClass($class);

            $noStaticFlag = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;

            $this->classes[$class]['properties'] = ['struct' => [], 'meta' => ['names' => []]];
            foreach ($refl->getProperties($noStaticFlag) as $reflProperty) {
                $this->classes[$class]['properties']['struct'][$reflProperty->getName()] = [
                    'refl' => $reflProperty, 'name' => $reflProperty->getName(), 'type' => null
                ];
                $this->classes[$class]['properties']['meta']['names'][] = $reflProperty->getName();
            }
        }

        return $this->classes[$class]['properties'];
    }

    /**
     * @param string $type
     * @param string $namespace
     *
     * @return string
     */
    protected function resolveType($type, $namespace)
    {
        if (in_array($type, ['boolean', 'float', 'integer', 'string', 'array'])) {
            $class = null;
        } else {
            $class = empty($namespace) ? $type : $this->classNameParser->join($namespace, $type);
            $type = 'object';
        }

        return [$type, $class];
    }

    /**
     * @param string $fullClass
     * @param string $method
     * @param string $pattern
     * @param string $tag
     *
     * @return string
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
                    if ('return_value' === $tag) {
                        $methods['struct'][$method][$tag] = $matches[1];
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
                            list($type, $paramFullClass) = $this->resolveType($match, $namespace);
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
     * @param string $class
     *
     * @return array
     */
    protected function getMethodsNames($class)
    {
        return $this->getMethods($class)['meta']['names'];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    protected function &getMethods($class)
    {
        if (! isset($this->classes[$class]['methods']['meta']['names'])) {
            $refl = $this->getReflectionClass($class);

            $noStaticFlag = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;

            $this->classes[$class]['methods']['meta']['names'] = [];
            $this->classes[$class]['methods']['struct'] = [];
            foreach ($refl->getMethods($noStaticFlag) as $reflMethod) {
                $this->classes[$class]['methods']['struct'][$reflMethod->getName()] = [
                    'refl' => $reflMethod,
                    'name' => $reflMethod->getName(),
                    'return_value' => null,
                    'args' => null
                ];
                $this->classes[$class]['methods']['meta']['names'][] = $reflMethod->getName();
            }
        }

        return $this->classes[$class]['methods'];
    }
}
