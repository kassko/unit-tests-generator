<?php

namespace Kassko\Test\UnitTestsGenerator\PhpGenerator;

use Kassko\Test\UnitTestsGenerator\AbstractPhpGenerator;

/**
 * Version56
 */
class Version56 extends AbstractPhpGenerator
{
    /**
     * @param string    $namespace      The namespace where the class to create is.
     * @param array     $namespacesUsed All the namespaces to use in the code.
     * @param string    $class          The name of the class to create.
     * @param string    $functions      The methods of the class to create.
     * @param string    $extendedClass (optional|null) The class that the class to create extends (if one).
     *
     * @return string   The generated code
     */
    public function generatePhpCode($namespace, array $namespacesUsed, $class, $functions, $extendedClass = null)
    {
        $testCode = <<<'TEST'
<?php

namespace {namespace};

{namespaces_used}

class {class} extends {extended_class}
{
{functions}
}

TEST;

        $testCode = str_replace('{namespace}', $namespace, $testCode);
        $testCode = str_replace('{namespaces_used}', $this->generateNamespacesUsedCode($namespacesUsed), $testCode);
        $testCode = str_replace('{class}', $class, $testCode);
        $testCode = str_replace('{functions}', $functions, $testCode);
        if (null !== $extendedClass) {
            $testCode = str_replace('{extended_class}', $extendedClass, $testCode);
        }

        return $testCode;
    }

    /**
     * @param array $namespacesUsed
     *
     * @return string The namespaces used code
     */
    public function generateNamespacesUsedCode(array $namespacesUsed)
    {
        return implode(
            $this->config['separator_line'],
            array_map(
                [$this, 'generateNamespaceUsedCode'],
                $namespacesUsed
            )
        );
    }

    /**
     * @param string $namespaceUsed
     *
     * @return string A namespace used code
     */
    public function generateNamespaceUsedCode($namespaceUsed)
    {
        $testCode = <<<'TEST'
use {namespace_used};
TEST;

        return str_replace('{namespace_used}', $namespaceUsed, $testCode);
    }
}
