<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\Faker;
use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\Model\Expression;
use Kassko\Test\UnitTestsGenerator\Model\Method;
use Kassko\Test\UnitTestsGenerator\Model\Parameter;
use Kassko\Test\UnitTestsGenerator\Model\Statement\Assert;
use Kassko\Test\UnitTestsGenerator\Model\Expression\NewAssign;
use Kassko\Test\UnitTestsGenerator\Model\Statement\CustomStmt;
use Kassko\Test\UnitTestsGenerator\Model\Value;
use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;
use Kassko\Test\UnitTestsGenerator\Visitor\NamespacesCollectorVisitor;
use InvalidArgumentException;

/**
 * TestCreator
 */
class TestCreator
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
     * @param string $fullClass
     */
    public function createTestForClass($fullClass)
    {
        $classModel = new ClassType($fullClass);

        $newStatements = $this->createTransversalsDependenciesNewStatements($classModel);
        foreach ($newStatements as $transversFullClass => $newStatement) {
            $classModel->addInitStatement($transversFullClass, $newStatement);
        }

        $method = $this->createSetup($classModel);
        $classModel->addMethod('setup', $method);

        $method = $this->createConstructorTest($classModel);
        $classModel->addMethod('__construct', $method);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);

        foreach ($this->reflector->getGetters($fullClass) as $getter) {
            $method = $this->createGetterTest($classModel, $getter);
            $classModel->addMethod($getter, $method);
        }

        foreach ($this->reflector->getSetters($fullClass) as $setter) {
            $method = $this->createSetterTest($classModel, $setter);
            $classModel->addMethod($setter, $method);
        }

        $namespacesCollectorVisitor = new NamespacesCollectorVisitor;
        $classModel->accept($namespacesCollectorVisitor);
        $classModel->useNamespaces($namespacesCollectorVisitor->getUsedNamespaces());

        return $classModel;
    }

    /**
     * @param ClassType $classModel
     */
    public function createSetup(ClassType $classModel)
    {
        $method = $classModel->createMethod('setup');

        $newStatements = $this->createClassAndDependenciesAssignStatements($classModel->getFullClass());
        $this->addInitStatementsToMethod($newStatements, $method);

        return $method;
    }

    /**
     * @param array     $initStatements
     * @param Method    $method
     */
    protected function addInitStatementsToMethod(array $initStatements, Method $method)
    {
        foreach ($initStatements as $fullClass => $initStatement) {
            $method->addInitStatement($fullClass, $initStatement);
        }
    }

    /**
     * @param ClassType $classModel
     *
     * @return Method
     */
    protected function createConstructorTest(ClassType $classModel)
    {
        $method = $classModel->createMethod('__construct');

        $fullClass = $classModel->getFullClass();
        $assignStatements = $this->createClassAndDependenciesAssignStatements($fullClass);
        $this->addInitStatementsToMethod($assignStatements, $method);

        $assertCode = [];
        foreach ($assignStatements[$fullClass]->getExpression()->getParameters() as $parameter) {
            $assert = new Assert\BinaryAssert(
                $parameter->getValue(),
                new Value\ExpressionValue(
                    new Expression\ReflPropertyGet(new Value\MemberNameValue($parameter->getParentObjectName()), $parameter->getParentPropertyName())
                ),
                'equals'
            );
            $method->addAssert($assert);
        }

        return $method;
    }

    /**
     * @param ClassType $classModel
     * @param string    $getter
     *
     * @return Method
     */
    protected function createGetterTest(ClassType $classModel, $getter)
    {
        $fullClass = $classModel->getFullClass();

        $method = $classModel->createMethod($getter);

        $property = $this->propertizeGetter($getter);
        $type = $this->reflector->getPropertyType($fullClass, $property);
        $value = $this->faker->generateValueFromType($type);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class);

        $expression = (new Expression\ReflPropertySet(new Value\MemberNameValue($objectName), $property, $value));

        $method->addCustomStmt(new CustomStmt($expression));

        $assert = new Assert\BinaryAssert(
            new Value\HighValue($value),
            new Value\ExpressionValue(new Expression\FuncCall(new Value\MemberNameValue($objectName), $getter)),
            'equals'
        );

        $method->addAssert($assert);

        return $method;
    }

    /**
     * @param ClassType $classModel
     * @param string    $setter
     *
     * @return Method
     */
    protected function createSetterTest(ClassType $classModel, $setter)
    {
        $fullClass = $classModel->getFullClass();
        $method = $classModel->createMethod($setter);

        $returnValue = $this->reflector->getMethodReturnValue($fullClass, $setter);

        $property = $this->propertizeSetter($setter);
        $type = $this->reflector->getPropertyType($fullClass, $property);
        $value = $this->faker->generateValueFromType($type);

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class);

        $memberNameValue = new Value\MemberNameValue($objectName);
        $highValueParam = new Parameter(new Value\HighValue($value));

        if ($returnValue === 'self' || $returnValue === $fullClass) {
            $assert = new Assert\BinaryAssert(
                $memberNameValue,
                new Value\ExpressionValue(
                    new Expression\FuncCall(
                        $memberNameValue, 
                        $setter, 
                        [$highValueParam]
                    )
                ),
                'equals'
            );
            $method->addAssert($assert);
        } else {
            $customStmt = new CustomStmt(
                new Expression\FuncCall(
                    new Value\MemberNameValue($objectName), $setter, [$highValueParam]
                )
            );
            $method->addCustomStmt($customStmt);
        }

        $assert = new Assert\BinaryAssert(
            new Value\HighValue($value),
            new Value\ExpressionValue(new Expression\ReflPropertyGet($memberNameValue, $property)),
            'equals'
        );
        $method->addAssert($assert);

        return $method;
    }

    /**
     * @param ClassType $classModel
     *
     * @return Statement[]
     */
    protected function createTransversalsDependenciesNewStatements(ClassType $classModel)
    {
        $statements = [];

        foreach ($this->config['tests_dep_fqcn'] as $depFullClass) {
            $class = $this->classNameParser->extractClassFromFullClass($depFullClass);
            $statements[$depFullClass] = new CustomStmt(
                new NewAssign(
                    new Value\MemberNameValue($this->generateAnObjectNameFromClass($class)),
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
     * @return Statement[]
     */
    protected function createClassAndDependenciesAssignStatements($fullClass)
    {
        $statements = [];

        $class = $this->classNameParser->extractClassFromFullClass($fullClass);
        $objectName = $this->generateAnObjectNameFromClass($class, $fullClass);

        $newAssignRoot = new NewAssign(new Value\MemberNameValue($objectName), $class, $fullClass);
        if ($this->reflector->hasConstructor($fullClass)) {
            $newAssignRoot->withConstructor();
        }
        $statements[$fullClass] = new CustomStmt($newAssignRoot);

        $params = $this->reflector->getConstructorParams($fullClass);
        if (null === $params) {
            return $statements;
        }

        foreach ((array)$params as $param) {
            if ('object' === $param['type']) {
                $statements[$param['full_class']] = $this->createParameterAssignStatement($param['full_class'], $param['name']);
                $newAssignRoot->addParameter(new Parameter(new Value\VariableNameValue($param['name']), $objectName, $param['name']));
            } else {
                $highValue = $this->faker->generateValueFromType($param['type']);
                $newAssignRoot->addParameter(new Parameter(new Value\HighValue($highValue), $objectName, $param['name']));
            }
        }

        return $statements;
    }

    /**
     * @param string $fullClass
     * @param string $collaboratorName
     *
     * @return Statement
     */
    protected function createParameterAssignStatement($fullClass, $collaboratorName)
    {
        $class = $this->classNameParser->extractClassFromFullClass($fullClass);

        $newAssign = (new NewAssign(new Value\VariableNameValue($collaboratorName), $class, $fullClass))->makeStub();

        if ($this->reflector->hasConstructor($fullClass)) {
            $newAssign->withConstructor();
        }

        return new CustomStmt($newAssign);
    }

    /**
     * @param string $getter
     *
     * @return string
     *
     * @throws InvalidArgumentException If the method does not seem to be a getter.
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

        throw new InvalidArgumentException(
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
