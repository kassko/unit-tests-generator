<?php

namespace Kassko\Test\UnitTestsGenerator\Util;

/**
 * PhpElementsExtractor
 */
class PhpElementsExtractor
{
    /**
     * @param string $filepath
     *
     * @return array
     */
    public function extractClassesFromFile($filepath)
    {
        $phpCode = file_get_contents($filepath);
        $fullClasses = $this->extractClassesFromCode($phpCode);

        return $fullClasses;
    }

    /**
     * @param string $phpCode
     *
     * @return array
     */
    protected function extractClassesFromCode($phpCode)
    {
        $fullClasses = [];
        $tokens = token_get_all($phpCode);
        $count = count($tokens);
        $namespace = '';

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

                case T_CLASS:
                    if ($i < $count - 2 && $tokens[$i + 1][0] == T_WHITESPACE && $tokens[$i + 2][0] == T_STRING) {
                        $fullClasses[] = $namespace . '\\' . $tokens[$i + 2][1];
                    }
                    break;
            }
        }

        return $fullClasses;
    }
}
