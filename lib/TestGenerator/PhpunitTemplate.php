<?php

namespace Kassko\Test\UnitTestsGenerator\TestGenerator;

/**
 * PhpunitTemplate
 */
class PhpunitTemplate
{
    /**
     * @return string
     */
    public static function method()
    {
        return <<<'TEST'
    /**
     * @test
     */
    public function {method}()
    {
{code}
    }
TEST;
    }

    /**
     * @return string
     */
    public static function methodSetup()
    {
        return <<<'TEST'
    public function setup()
    {
{ta_code}
{code}
    }
TEST;
    }

    /**
     * @return string
     */
    public static function assignInstantiation()
    {
        return <<<'TEST'
{obj} = new {class}{params};
TEST;
    }

    /**
     * @return string
     */
    public static function assignStubInstantiation()
    {
        return <<<'TEST'
{obj} = $this->getMockBuilder({class}::class)->disableOriginalConstructor()->getMock();
TEST;
    }

    /**
     * @return string
     */
    public static function exec()
    {
        return <<<'TEST'
{expr};
TEST;
    }

    /**
     * @return string
     */
    public static function assertEquals()
    {
        return <<<'TEST'
$this->assertEquals({expected}, {actual});
TEST;
    }

    /**
     * @return string
     */
    public static function assertTrue()
    {
        return <<<'TEST'
$this->assertTrue({expected});
TEST;
    }

    /**
     * @return string
     */
    public static function exprReflPropertyGet()
    {
        return <<<'TEST'
$this->objectMemberAccessor->getPropertyValue({obj}, '{prop}')
TEST;
    }

    /**
     * @return string
     */
    public static function exprReflPropertySet()
    {
        return <<<'TEST'
$this->objectMemberAccessor->setPropertyValue({obj}, '{prop}', {val})
TEST;
    }

    public static function exprFuncCall()
    {
        return <<<'TEST'
{obj}->{func}({params})
TEST;
    }

    /**
     * Private constructor. This class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Private magic clone method. This class cannot be cloned.
     */
    private function __clone()
    {
    }
}
