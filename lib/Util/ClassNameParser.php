<?php

namespace Kassko\Test\UnitTestsGenerator\Util;

/**
 * ClassNameParser
 */
class ClassNameParser
{
    /**
     * @param string $namespace
     * @param string $class
     *
     * @return string
     */
    public function join($namespace, $class)
    {
        return $namespace . '\\' . $class;
    }

    /**
     * @param string $fullClass
     *
     * @return array Returns an array with first the namespace and second the class name.
     */
    public function tokenizeFullClass($fullClass)
    {
        $classSeparatorIndex = strrpos($fullClass, '\\');
        if (false === $classSeparatorIndex) {
            return ['', $fullClass];
        }

        return [
            substr($fullClass, 0, $classSeparatorIndex), //namespace
            substr($fullClass, $classSeparatorIndex+1) //class
        ];
    }

    /**
     * @param string $fullClass
     *
     * @return string
     */
    public function extractNamespaceFromFullClass($fullClass)
    {
        $classSeparatorIndex = strrpos($fullClass, '\\');
        if (false === $classSeparatorIndex) {
            return '';
        }

        return substr($fullClass, 0, strrpos($fullClass, '\\'));
    }

    /**
     * @param string $fullClass
     *
     * @return string
     */
    public function extractClassFromFullClass($fullClass)
    {
        $classSeparatorIndex = strrpos($fullClass, '\\');
        if (false === $classSeparatorIndex) {
            return $fullClass;
        }

        return substr($fullClass, strrpos($fullClass, '\\') + 1);
    }
}
