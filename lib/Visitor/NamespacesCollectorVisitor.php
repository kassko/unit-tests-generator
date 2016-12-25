<?php

namespace Kassko\Test\UnitTestsGenerator\Visitor;

use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\Model\Expression\NewAssign;

/**
 * NamespacesCollectorVisitor
 */
class NamespacesCollectorVisitor
{
    /**
     * @var array
     */
    private $usedNamespaces = [];

    /**
     * @return array
     */
    public function getUsedNamespaces()
    {
        sort($this->usedNamespaces);

        return $this->usedNamespaces;
    }

    /**
     * @param ClassType $classModel
     */
    public function visit(ClassType $classModel)
    {
        $this->collectNamespaces($classModel->getInitStatements());

        foreach ($classModel->getMethods() as $method) {
            $this->collectNamespaces($method->getInitStatements());
        }
    }

    /**
     * @param Statement[] $statements
     */
    protected function collectNamespaces(array $statements)
    {
        foreach ($statements as $statement) {
            $newAssign = $statement->getExpression();
            if (!$newAssign instanceof NewAssign) {
                continue;
            }
            $fullClass = $newAssign->getFullClass();
            $this->usedNamespaces[$fullClass] = $fullClass;
        }
    }
}
