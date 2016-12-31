<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\CodeModel;
use Kassko\Test\UnitTestsGenerator\CodeModelCreator;
use Kassko\Test\UnitTestsGenerator\Faker;
use Kassko\Test\UnitTestsGenerator\NamespacesCollectorVisitor;
use Kassko\Test\UnitTestsGenerator\PlanModel;
use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;

/**
 * CodeModelCreator
 */
class CodeModelCreator
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var ClassNameParser
     */
    private $classNameParser;
    /**
     * @var Reflector
     */
    private $reflector;
    /**
     * @var Faker
     */
    private $faker;

    /**
     * @param array             $config
     * @param ClassNameParser   $classNameParser
     * @param Reflector         $reflector
     * @param Faker             $faker
     */
    public function __construct(array $config, ClassNameParser $classNameParser, Reflector $reflector, Faker $faker)
    {
        $this->config = $config;
        $this->classNameParser = $classNameParser;
        $this->reflector = $reflector;
        $this->faker = $faker;
    }

    /**
     * @param CodeModel\Class_ $classCodeModel
     *
     * @return CodeModel\Class_
     */
    public function loadCodeModel(CodeModel\Class_ $classCodeModel)
    {
        $fullClass = $classCodeModel->getFullClass();

        $newStatements = $this->createTransversalsDependenciesNewStatements($classCodeModel);
        foreach ($newStatements as $transversFullClass => $newStatement) {
            $classCodeModel->addInitStatement($transversFullClass, $newStatement);
        }

        $method = $this->createSetup($classCodeModel);
        $classCodeModel->addMethod($method);

        $method = $this->createConstructorTest($classCodeModel);
        $classCodeModel->addMethod($method);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);

        foreach ($this->reflector->getGetters($fullClass) as $getter) {
            $method = $this->createGetterTest($classCodeModel, $getter);
            $classCodeModel->addMethod($method);
        }

        foreach ($this->reflector->getSetters($fullClass) as $setter) {
            $method = $this->createSetterTest($classCodeModel, $setter);
            $classCodeModel->addMethod($method);
        }

        $namespacesCollectorVisitor = new NamespacesCollectorVisitor;
        $classCodeModel->accept($namespacesCollectorVisitor);
        $classCodeModel->useNamespaces($namespacesCollectorVisitor->getUsedNamespaces());

        return $classCodeModel;
    }

    /**
     * @param CodeModel\Class_ $classCodeModel
     *
     * @return CodeModel\Class_
     */
    public function completeCodeModelFromPlanModel(CodeModel\Class_ $classCodeModel, PlanModel\Class_ $classPlanModel)
    {
        //...

        return $classCodeModel;
    }

    /**
     * @param CodeModel\Class_ $classModel
     *
     * @return CodeModel\Method
     */
    public function createSetup(CodeModel\Class_ $classModel)
    {
        $method = $classModel->createMethod('setup');

        $newStatements = $this->createClassAndDependenciesAssignStatements($classModel->getFullClass());
        $this->addInitStatementsToMethod($newStatements, $method);

        return $method;
    }

    /**
     * @param array     $initStatements
     * @param CodeModel\Method    $method
     */
    protected function addInitStatementsToMethod(array $initStatements, CodeModel\Method $method)
    {
        foreach ($initStatements as $fullClass => $initStatement) {
            $method->addInitStatement($fullClass, $initStatement);
        }
    }

    /**
     * @param CodeModel\Class_ $classModel
     *
     * @return CodeModel\Method
     */
    protected function createConstructorTest(CodeModel\Class_ $classModel)
    {
        $method = $classModel->createMethod('constructor');

        $fullClass = $classModel->getFullClass();
        $assignStatements = $this->createClassAndDependenciesAssignStatements($fullClass);
        $this->addInitStatementsToMethod($assignStatements, $method);

        $assertCode = [];
        foreach ($assignStatements[$fullClass]->getExpression()->getParameters() as $parameter) {
            $assert = new CodeModel\Statement\Assert\BinaryAssert(
                $parameter->getValue(),
                new CodeModel\Value\ExpressionValue(
                    new CodeModel\Expression\ReflPropertyGet(new CodeModel\Value\MemberNameValue($parameter->getParentObjectName()), $parameter->getParentPropertyName())
                ),
                'equals'
            );
            $method->addAssert($assert);
        }

        return $method;
    }

    /**
     * @param CodeModel\Class_ $classModel
     * @param string    $getter
     *
     * @return CodeModel\Method
     */
    protected function createGetterTest(CodeModel\Class_ $classModel, $getter)
    {
        $fullClass = $classModel->getFullClass();

        $method = $classModel->createMethod($getter);

        $property = $this->propertizeGetter($getter);

        $typeInfo = $this->reflector->getPropertyType($fullClass, $property);
        $value = $this->faker->generateValueFromType($typeInfo['type'], $typeInfo['full_class']);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class);

        $expression = (new CodeModel\Expression\ReflPropertySet(new CodeModel\Value\MemberNameValue($objectName), $property, $value));

        $method->addCustomStmt(new CodeModel\Statement\CustomStmt($expression));

        $assert = new CodeModel\Statement\Assert\BinaryAssert(
            new CodeModel\Value\HighValue($value),
            new CodeModel\Value\ExpressionValue(new CodeModel\Expression\FuncCall(new CodeModel\Value\MemberNameValue($objectName), $getter)),
            'equals'
        );

        $method->addAssert($assert);

        return $method;
    }

    /**
     * @param CodeModel\Class_ $classModel
     * @param string    $setter
     *
     * @return CodeModel\Method
     */
    protected function createSetterTest(CodeModel\Class_ $classModel, $setter)
    {
        $fullClass = $classModel->getFullClass();
        $method = $classModel->createMethod($setter);

        $returnTypeInfo = $this->reflector->getMethodReturnType($fullClass, $setter);

        $property = $this->propertizeSetter($setter);
        $typeInfo = $this->reflector->getPropertyType($fullClass, $property);
        $value = $this->faker->generateValueFromType($typeInfo['type'], $typeInfo['full_class']);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class);

        $memberNameValue = new CodeModel\Value\MemberNameValue($objectName);
        $highValueParam = new CodeModel\Parameter(new CodeModel\Value\HighValue($value));

        if ($returnTypeInfo['full_class'] === $fullClass) {
            $assert = new CodeModel\Statement\Assert\BinaryAssert(
                $memberNameValue,
                new CodeModel\Value\ExpressionValue(
                    new CodeModel\Expression\FuncCall(
                        $memberNameValue,
                        $setter,
                        [$highValueParam]
                    )
                ),
                'equals'
            );
            $method->addAssert($assert);
        } else {
            $customStmt = new CodeModel\Statement\CustomStmt(
                new CodeModel\Expression\FuncCall(
                    new CodeModel\Value\MemberNameValue($objectName), $setter, [$highValueParam]
                )
            );
            $method->addCustomStmt($customStmt);
        }

        $assert = new CodeModel\Statement\Assert\BinaryAssert(
            new CodeModel\Value\HighValue($value),
            new CodeModel\Value\ExpressionValue(new CodeModel\Expression\ReflPropertyGet($memberNameValue, $property)),
            'equals'
        );
        $method->addAssert($assert);

        return $method;
    }

    /**
     * @param CodeModel\Class_ $classModel
     *
     * @return CodeModel\Statement[]
     */
    protected function createTransversalsDependenciesNewStatements(CodeModel\Class_ $classModel)
    {
        $statements = [];

        foreach ($this->config['tests_dep_fqcn'] as $depFullClass) {
            $class = $this->classNameParser->extractClassFromFullClass($depFullClass);
            $statements[$depFullClass] = new CodeModel\Statement\CustomStmt(
                new CodeModel\Expression\NewAssign(
                    new CodeModel\Value\MemberNameValue($this->generateAnObjectNameFromClass($class)),
                    $class,
                    $depFullClass
                )
            );
        }

        return $statements;
    }

    /**
     * @param string $fullClass
     *
     * @return CodeModel\Statement[]
     */
    protected function createClassAndDependenciesAssignStatements($fullClass)
    {
        $statements = [];

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class, $fullClass);

        $newAssignRoot = new CodeModel\Expression\NewAssign(new CodeModel\Value\MemberNameValue($objectName), $class, $fullClass);
        if ($this->reflector->hasConstructor($fullClass)) {
            $newAssignRoot->withConstructor();
        }
        $statements[$fullClass] = new CodeModel\Statement\CustomStmt($newAssignRoot);

        $params = $this->reflector->getConstructorParams($fullClass);
        if (null === $params) {
            return $statements;
        }

        foreach ((array)$params as $param) {
            if ('object' === $param['type']) {
                $statements[$param['full_class']] = $this->createParameterAssignStatement($param['full_class'], $param['name']);
                $newAssignRoot->addParameter(new CodeModel\Parameter(new CodeModel\Value\VariableNameValue($param['name']), $objectName, $param['name']));
            } else {
                $highValue = $this->faker->generateValueFromType($param['type'], $param['full_class']);
                $newAssignRoot->addParameter(new CodeModel\Parameter(new CodeModel\Value\HighValue($highValue), $objectName, $param['name']));
            }
        }

        return $statements;
    }

    /**
     * @param string $fullClass
     * @param string $collaboratorName
     *
     * @return CodeModel\Statement
     */
    protected function createParameterAssignStatement($fullClass, $collaboratorName)
    {
        $class = $this->classNameParser->extractClassFromFullClass($fullClass);

        $newAssign = (new CodeModel\Expression\NewAssign(new CodeModel\Value\VariableNameValue($collaboratorName), $class, $fullClass))->makeStub();

        if ($this->reflector->hasConstructor($fullClass)) {
            $newAssign->withConstructor();
        }

        return new CodeModel\Statement\CustomStmt($newAssign);
    }

    /**
     * @param string $getter
     *
     * @return string
     *
     * @throws \InvalidArgumentException If the method does not seem to be a getter.
     */
    protected function propertizeGetter($getter)
    {
        $getterBegin = substr($getter, 0, 3);

        if ('get' === $getterBegin) {
            return lcfirst(substr($getter, 3));
        } elseif ('is' === substr($getterBegin, 2)) {
            return lcfirst(substr($getter, 2));
        } elseif ('has' === $getterBegin) {
            return lcfirst(substr($getter, 3));
        }

        throw new \InvalidArgumentException(
            sprintf('Bad getter "%s". A getter should start with "get", "is" or "has".', $getter)
        );
    }

    /**
     * @param string $setter
     *
     * @return string
     */
    protected function propertizeSetter($setter)
    {
        return lcfirst(substr($setter, 3));
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function generateAnObjectNameFromClass($class)
    {
        return lcfirst($class);
    }
}
