<?php

namespace Kassko\Test\UnitTestsGeneratorTest;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Kassko\Test\UnitTestsGenerator\CodeDumper;
use Kassko\Test\UnitTestsGenerator\CodeModelCreator;
use Kassko\Test\UnitTestsGenerator\Faker;
use Kassko\Test\UnitTestsGenerator\FilesFinder;
use Kassko\Test\UnitTestsGenerator\GenerateTestsCommand;
use Kassko\Test\UnitTestsGenerator\OutputDirectoryResolver;
use Kassko\Test\UnitTestsGenerator\OutputNamespaceResolver;
use Kassko\Test\UnitTestsGenerator\PhpGenerator;
use Kassko\Test\UnitTestsGenerator\PlanLoader\AnnotationLoader;
use Kassko\Test\UnitTestsGenerator\PlanLoader\ArrayLoader;
use Kassko\Test\UnitTestsGenerator\TestGenerator;
use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use Kassko\Test\UnitTestsGenerator\Util\PhpElementsExtractor;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;
use Symfony\Component\Filesystem\Filesystem;

class GenerateTestsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        AnnotationRegistry::registerLoader('class_exists');

        $classNameParser = new ClassNameParser;
        $reflector = new Reflector($classNameParser);
        $faker = new Faker;

        $this->generateTestsCommand = new GenerateTestsCommand(
            [],
            new FilesFinder(['input_files_locations' => [__DIR__ . '/Fixtures/']]),
            new PhpElementsExtractor,
            new CodeModelCreator(
                [
                    'tests_dep_fqcn' => ['Kassko\\Util\\MemberAccessor\\ObjectMemberAccessor']
                ],
                $classNameParser,
                $reflector,
                $faker
            ),
            new TestGenerator\Phpunit\Version4(
                [
                    'separator_line' => "\r\n",
                    'tabulation' => "    ",
                    'first_level_indent' => "        ",
                ],
                new PhpGenerator\Version56(['separator_line' => "\r\n"]),
                $reflector,
                $faker,
                new OutputNamespaceResolver([
                    'namespaces' => [
                        'psr4_prefix' => ['nb_levels' => 3]
                    ]
                ]),
                $classNameParser
            ),
            new CodeDumper(new Filesystem),
            new OutputDirectoryResolver([
                'files' => [
                    'map' => [
                        __DIR__ . '\\Fixtures\\' => __DIR__ . '\\FixturesTests\\',
                        //$dir . '\\src\\' => $dir . '\\tests\\auto\\'
                    ],
                    //'unique' => __DIR__ . '\\FixturesTests\\',
                ],
            ]),
            new AnnotationLoader(new AnnotationReader, $reflector, new ArrayLoader)
        );
    }

    /**
     * @test
     */
    public function process()
    {
        $this->generateTestsCommand->process();
    }
}
