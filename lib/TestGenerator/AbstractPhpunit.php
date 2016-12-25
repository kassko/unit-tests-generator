<?php

namespace Kassko\Test\UnitTestsGenerator\TestGenerator;

use InvalidArgumentException;
use Kassko\Test\UnitTestsGenerator\AbstractTestGenerator;
use Kassko\Test\UnitTestsGenerator\Model\AbstractSimpleValue;
use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\Model\Expression;
use Kassko\Test\UnitTestsGenerator\Model\Method;
use Kassko\Test\UnitTestsGenerator\Model\Parameter;
use Kassko\Test\UnitTestsGenerator\Model\Statement\Assert;
use Kassko\Test\UnitTestsGenerator\Model\Statement\Assignment;
use Kassko\Test\UnitTestsGenerator\Model\Value;
use Kassko\Test\UnitTestsGenerator\TestGenerator;

/**
 * AbstractPhpunit
 */
abstract class AbstractPhpunit extends AbstractTestGenerator
{
    /**
     * @param ClassType $classModel
     *
     * @return string
     */
    public function generateCodeForTest(ClassType $classModel)
    {
        $fullClass = $classModel->getFullClass();

        list($namespace, $class) = $this->classNameParser->tokenizeFullClass($fullClass);
        $testNamespace = $this->outputNamespaceResolver->resolveTestNamespace($namespace);

        $functionsCode = [];

        $tranversalsAssignCode = $this->generateTransversalsAssignmentsCode($classModel);
        $functionsCode[] = $this->generateSetupTest($classModel->getMethod('setup'), $tranversalsAssignCode);

        foreach ($classModel->getMethods() as $method) {
            if ('setup' === $method->getName()) {
                continue;
            }

            $functionsCode[] = $this->generateMethodTest($method);
        }

        $namespacesUsed = $classModel->getUsedNamespaces();
        $functionsCode = implode($this->config['separator_line'] . $this->config['separator_line'], $functionsCode);
        sort($namespacesUsed);

        return $this->phpGenerator->generatePhpCode(
            $testNamespace,
            $namespacesUsed,
            $class . 'Test',
            $functionsCode,
            '\PHPUnit_Framework_TestCase'
        );
    }

    /**
     * @param ClassType $classModel
     *
     * @return string
     */
    protected function generateTransversalsAssignmentsCode(ClassType $classModel)
    {
        $assignmentsCode = [];

        foreach ($classModel->getInitStatements() as $initStatement) {
            $assignment = $initStatement->getExpression();
            $class = $assignment->getClass();

            $testCode = str_replace('{class}', $class, PhpunitTemplate::assignInstantiation());
            $testCode = str_replace('{obj}', $assignment->getObjectValue()->getAsScalar(), $testCode);
            $testCode = str_replace('{params}', '', $testCode);

            $initStatementsCode[] = $testCode;
        }

        $testCode = 
        $this->config['first_level_indent'] 
        . implode(
            $this->config['separator_line'] . $this->config['first_level_indent'], 
            $initStatementsCode
        );

        return $testCode;
    }

    /**
     * @param Method $method
     * @param string $tranversalsAssignCode
     *
     * @return string
     */
    protected function generateSetupTest(Method $method, $tranversalsAssignCode)
    {
        $bodyCode = $this->generateMethodBodyCode($method);
        var_dump($tranversalsAssignCode);

        $code = str_replace(
            ['{method}', '{ta_code}', '{code}'], 
            [$method->getName(), $tranversalsAssignCode, $bodyCode], 
            PhpunitTemplate::methodSetup()
        );

        return $code;
    }

    /**
     * @param Method $method
     *
     * @return string
     */
    protected function generateMethodTest(Method $method)
    {
        if ('setup' === $method->getName()) {
            $this->generateTransversalsAssignmentsCode($method->getClassType());
        }

        $bodyCode = $this->generateMethodBodyCode($method);

        $code = str_replace(['{method}', '{code}'], [$method->getName(), $bodyCode], PhpunitTemplate::method());

        return $code;
    }

    protected function generateMethodBodyCode(Method $method)
    {
        $assignmentsCode = $this->generateInitStatementsCode($method->getOrderedInitStatements());
        $spiesCode = $this->generateSpiesCode($method->getSpies());
        $customStmtsCode = $this->generateCustomStmtsCode($method->getCustomStmts());
        $assertsCode = $this->generateAssertsCode($method->getAsserts());

        $code = '';
        $separatorLine = '';

        if (!empty($assignmentsCode)) {
            $code = $assignmentsCode;
            $separatorLine = $this->config['separator_line'];
        }

        if (!empty($spiesCode)) {
            $code .= $separatorLine . $spiesCode;
            $separatorLine = $this->config['separator_line'];
        }

        if (!empty($customStmtsCode)) {
            $code .= $separatorLine . $customStmtsCode;
            $separatorLine = $this->config['separator_line'];
        }

        if (!empty($assertsCode)) {
            $code .= $separatorLine . $assertsCode;
        } 

        return $code;
    }

    /**
     * @param Statement[] $statements
     *
     * @return string
     */
    protected function generateInitStatementsCode(array $statements)
    {
        $statementsCode = [];
        foreach ($statements as $statement) {
            $assignment = $statement->getExpression();
            if (!$assignment->isStub()) {
                $statementCode = str_replace(
                    '{obj}',
                    $assignment->getObjectValue()->getAsScalar(),
                    PhpunitTemplate::assignInstantiation()
                );
                $statementCode = str_replace('{class}', $assignment->getClass(), $statementCode);
            } else {
                $statementCode = str_replace(
                    '{obj}',
                    $assignment->getObjectValue()->getAsScalar(),
                    PhpunitTemplate::assignStubInstantiation()
                );
                $statementCode = str_replace('{class}', $assignment->getClass(), $statementCode);
                if ($assignment->hasConstructor()) {
                    $statementCode = str_replace('disableOriginalConstructor()->', '', $statementCode);
                }
            }
            
            $parametersCode = $this->generateParametersCode($assignment->getParameters());
            $statementCode = str_replace(
                '{params}', 
                empty($parametersCode) ? '' : '(' . $parametersCode . ')', 
                $statementCode
            );

            $statementsCode[] = $statementCode;
        }

        return $this->formatCode($statementsCode);
    }

    /**
     * @param Spy[] $spies
     *
     * @return string
     */
    protected function generateSpiesCode(array $spies)
    {
        return '';
    }

    /**
     * @param CustomStmt[]
     *
     * @return string
     */
    protected function generateCustomStmtsCode(array $customStmts)
    {
        $customStmtsCode = [];

        foreach ($customStmts as $customStmt) {
            $expression = $customStmt->getExpression();
            switch (true) {
                case $expression instanceof Expression\ReflPropertySet:
                    $expressionCode = str_replace(
                        ['{obj}', '{prop}', '{val}'],
                        [
                            $expression->getObjectValue()->getAsScalar(),
                            $expression->getPropertyName(),
                            $expression->getPropertyValue()
                        ],
                        PhpunitTemplate::exprReflPropertySet()
                    );

                    $customStmtsCode[] = str_replace('{expr}', $expressionCode, PhpunitTemplate::exec());
                    break;

                case $expression instanceof Expression\FuncCall:
                    $expressionCode = str_replace(
                        ['{obj}', '{func}', '{params}'],
                        [
                            $expression->getObjectValue()->getAsScalar(),
                            $expression->getFuncName(),
                            $this->generateParametersCode($expression->getParameters())
                        ],
                        PhpunitTemplate::exprFuncCall()
                    );

                    $customStmtsCode[] = str_replace('{expr}', $expressionCode, PhpunitTemplate::exec());
                    break;
            }
        }

        return $this->formatCode($customStmtsCode);
    }

    /**
     * @param Assert[]
     *
     * @return string
     */
    protected function generateAssertsCode(array $asserts)
    {
        $assertsCode = [];
        foreach ($asserts as $assert) {
            switch (true) {
                case $assert instanceof Assert\UnaryAssert:
                    $operandCode = $this->generateOperandCode($assert->getOperand());

                    $assertsCode[] = str_replace(['{expected}'], [$operandCode], PhpunitTemplate::assertTrue());
                    break;

                case $assert instanceof Assert\BinaryAssert:
                    $leftOperandCode = $this->generateOperandCode($assert->getLeftOperand());
                    $rightOperandCode = $this->generateOperandCode($assert->getRightOperand());

                    $assertsCode[] = str_replace(
                        ['{expected}', '{actual}'],
                        [$leftOperandCode, $rightOperandCode],
                        PhpunitTemplate::assertEquals()
                    );
                    break;
            }
        }

        return $this->formatCode($assertsCode);
    }

    /**
     * @param Value $operand
     *
     * @return string
     */
    protected function generateOperandCode(Value $operand)
    {
        $code = null;

        switch (true) {
            case $operand instanceof AbstractSimpleValue:
                $code = $operand->getAsScalar();
                break;

            case $operand instanceof Value\ExpressionValue:
                $expression = $operand->getValue();

                switch (true) {
                    case $expression instanceof Expression\FuncCall:
                        $code = str_replace(
                            ['{obj}', '{func}', '{params}'],
                            [
                                $expression->getObjectValue()->getAsScalar(),
                                $expression->getFuncName(),
                                $this->generateParametersCode($expression->getParameters()),
                            ],
                            PhpunitTemplate::exprFuncCall()
                        );
                        break;
                    case $expression instanceof Expression\ReflPropertyGet:
                        $code = str_replace(
                            ['{obj}', '{prop}'],
                            [
                                $expression->getObjectValue()->getAsScalar(),
                                $expression->getPropertyName(),
                            ],
                            PhpunitTemplate::exprReflPropertyGet()
                        );
                        break;
                }
                break;
        }

        return $code;
    }

    /**
     * @param array $parameters (default)
     */
    protected function generateParametersCode(array $parameters = [])
    {
        if (!count($parameters)) {
            return '';
        } 

        return implode(
            ', ',
             array_map(
                function ($parameter) {
                    return $parameter->getValue()->getAsScalar();
                },
                $parameters
            )
        );
    }

    /**
     * @param array $codeLines
     *
     * @return string
     */
    protected function formatCode(array $codeLines)
    {
        $code = implode($this->config['separator_line'] . $this->config['first_level_indent'], $codeLines);
        if (empty($code)) {
            return $code;
        }

        return $this->config['first_level_indent'] . $code;
    }
}
