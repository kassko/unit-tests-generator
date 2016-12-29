<?php

namespace Kassko\Test\UnitTestsGenerator\Util;

/**
 * PhpElementsExtractor
 */
class PhpElementsExtractor
{
    /**
     * @var array
     */
    private $classesInfo = [];

    /**
     * @param string $filepath
     *
     * @return array
     */
    public function parseFile($filepath)
    {
        $phpCode = file_get_contents($filepath);
        $this->parseCode($phpCode);
    }

    /**
     * @param string $phpCode
     *
     * @return array
     */
    protected function parseCode($phpCode)
    {
        $fullClasses = [];
        $tokens = token_get_all($phpCode);
        $count = count($tokens);
        $namespace = '';
        $usedClasses = [];
        $usedClassesAliases = [];

        for ($i = 0; $i < $count; $i++) {
            switch ($tokens[$i][0]) {
                case T_NAMESPACE:
                    for ($i_ns = $i; $i_ns < $count && ';' != $tokens[$i_ns][0]; $i_ns++) {
                        switch ($tokens[$i_ns][0]) {
                            case T_STRING:
                                $namespace .= $tokens[$i_ns][1];
                                break;
                            case T_NS_SEPARATOR:
                                $namespace .= '\\';
                                break;
                        }
                    }
                    break;

                case T_USE:
                    $use = '';
                    $shortClass = null;
                    $alias = null;

                    for ($i_ns = $i; $i_ns < $count && ';' != $tokens[$i_ns][0]; $i_ns++) {
                        switch ($tokens[$i_ns][0]) {
                            case T_STRING:
                                if ('as' !== strtolower($tokens[$i_ns][1])) {
                                    $use .= $tokens[$i_ns][1];
                                    $shortClass = $tokens[$i_ns][1];
                                } else {
                                    $alias = $tokens[$i_ns][1];
                                }
                                break;
                            case T_NS_SEPARATOR:
                                $use .= '\\';
                                break;
                        }
                    }

                    if (isset($alias)) {
                        $usedClassesAliases[$alias] = ['use' => $use, 'short_class' => $shortClass];
                    } else {
                        $usedClasses[$shortClass] = ['use' => $use, 'alias' => $alias];
                    }
                    break;

                case T_CLASS:
                    if ($i < $count - 2 && $tokens[$i + 1][0] == T_WHITESPACE && $tokens[$i + 2][0] == T_STRING) {
                        $fullClass = $namespace . '\\' . $tokens[$i + 2][1];
                        
                        $this->classesInfo[$fullClass] = ['used_classes' => $usedClasses, 'used_classes_aliases' => $usedClassesAliases];
                        $usedClasses = [];
                        $usedClassesAliases = [];
                    }
                    break;
            }
        }
    }

    public function getFullClasses()
    {
        return array_keys($this->classesInfo);
    }

    public function getUsedClassesInClass($fullClass)
    {
        return $this->classesInfo[$fullClass]['used_classes'];
    }

    public function getUsedClassesAliasesInClass($fullClass)
    {
        return $this->classesInfo[$fullClass]['used_classes_aliases'];
    }
}
