<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\AbstractPhpGenerator;
use Kassko\Test\UnitTestsGenerator\Faker;
use Kassko\Test\UnitTestsGenerator\Model\ClassType;
use Kassko\Test\UnitTestsGenerator\OutputNamespaceResolver;
use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;
use ReflectionClass;

/**
 * AbstractTestGenerator
 */
abstract class AbstractTestGenerator
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var AbstractPhpGenerator
     */
    protected $phpGenerator;
    /**
     * @var Reflector
     */
    protected $reflector;
    /**
     * @var Faker
     */
    protected $faker;
    /**
     * @var OutputNamespaceResolver
     */
    protected $outputNamespaceResolver;
    /**
     * @var ClassNameParser
     */
    protected $classNameParser;

    /**
     * @param array             $config
     * @param AbstractPhpGenerator      $phpGenerator
     * @param Reflector         $reflector
     * @param Faker             $faker
     * @param OutputNamespaceResolver $outputNamespaceResolver
     * @param ClassNameParser   $classNameParser
     */
    public function __construct(
        array $config,
        AbstractPhpGenerator $phpGenerator,
        Reflector $reflector,
        Faker $faker,
        OutputNamespaceResolver $outputNamespaceResolver,
        ClassNameParser $classNameParser
    ) {
        $this->config = $config;
        $this->phpGenerator = $phpGenerator;
        $this->reflector = $reflector;
        $this->faker = $faker;
        $this->outputNamespaceResolver = $outputNamespaceResolver;
        $this->classNameParser = $classNameParser;
    }

    /**
     * @param ClassType $classModel
     *
     * @return string
     */
    abstract public function generateCodeForTest(ClassType $classModel);
}
