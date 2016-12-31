<?php

namespace Kassko\Test\UnitTestsGenerator\Util;

/**
 * PhpElementsParser
 */
class PhpElementsParser
{
    /**
     * @var array
     */
    private $classesInfo = [];
    /**
     * @var array
     */
    private $mapFilePathToFullClass = [];

    /**
     * @param string $filepath
     *
     * @return array
     */
    public function parseFile($filepath)
    {
        $phpCode = file_get_contents($filepath);
        $this->parseCode($phpCode, $filepath);
    }

    /**
     * @param string $phpCode
     * @param string $filePath
     *
     * @return array
     */
    protected function parseCode($phpCode, $filepath)
    {
        $fullClasses = [];
        $tokens = token_get_all($phpCode);
        $count = count($tokens);
        $namespace = '';
        $mapShortClassToFullClass = [];
        $mapAliasToUse = [];
        $this->mapFilePathToFullClass[$filepath] = [];

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
                                if (null === $alias) {
                                    $use .= $tokens[$i_ns][1];
                                    $shortClass = $tokens[$i_ns][1];
                                } elseif (true === $alias) {
                                    $alias = $tokens[$i_ns][1];
                                }
                                break;
                            case T_AS:
                                $alias = true;
                                break;
                            case T_NS_SEPARATOR:
                                $use .= '\\';
                                break;
                        }
                    }

                    if (isset($alias)) {
                        $mapAliasToUse[$alias] = $use;
                    } else {
                        $mapShortClassToFullClass[$shortClass] = $use;
                    }
                    break;

                case T_CLASS:
                    if ($i < $count - 2 && $tokens[$i + 1][0] == T_WHITESPACE && $tokens[$i + 2][0] == T_STRING) {
                        $fullClass = $namespace . '\\' . $tokens[$i + 2][1];

                        $this->mapFilePathToFullClass[$filepath][] = $fullClass;
                        $this->classesInfo[$fullClass] = [
                            'map_short_class_to_full_class' => $mapShortClassToFullClass,
                            'map_alias_to_use' => $mapAliasToUse
                        ];
                        $mapShortClassToFullClass = [];
                        $mapAliasToUse = [];
                    }
                    break;
            }
        }
    }

    /**
     * @param string $parentFullClass
     * @param string $classOrRelativeClassPath
     *
     * @return string
     *
     * @throws \DomainException
     */
    public function resolveFullClass($parentFullClass, $classOrRelativeClassPath)
    {
        $classInfo = $this->classesInfo[$parentFullClass];

        $mapShortClassToFullClass = $classInfo['map_short_class_to_full_class'];
        if (isset($mapShortClassToFullClass[$classOrRelativeClassPath])) {
            return $mapShortClassToFullClass[$classOrRelativeClassPath];
        }


        $mapAliasToUse = $classInfo['map_alias_to_use'];
        $rootClassPath = substr($classOrRelativeClassPath, strpos($classOrRelativeClassPath, '\\'));
        if (isset($mapAliasToUse[$rootClassPath])) {
            $pos = strpos($classOrRelativeClassPath, '\\');
            if (false === $pos) {
                return $mapAliasToUse[$rootClassPath];
            }
            return $mapAliasToUse[$rootClassPath] . '\\' . substr($classOrRelativeClassPath, $pos + 1);
        }

        return substr($parentFullClass, 0, strrpos($parentFullClass, '\\')) . '\\' . $classOrRelativeClassPath;

        /*
        throw new \DomainException(
            sprintf(
                'Cannot resolve full qualified class name from symbol "%s" in class "%s"',
                $classOrRelativeClassPath,
                $parentFullClass
            )
        );
        */
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getFullClasses($filePath)
    {
        return $this->mapFilePathToFullClass[$filePath];
    }
}
