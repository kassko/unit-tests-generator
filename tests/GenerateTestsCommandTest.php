<?php

namespace Kassko\Test\UnitTestsGeneratorTest;

use Kassko\Test\UnitTestsGenerator\Faker;
use Kassko\Test\UnitTestsGenerator\FilesFinder;
use Kassko\Test\UnitTestsGenerator\GenerateTestsCommand;
use Kassko\Test\UnitTestsGenerator\OutputDirectoryResolver;
use Kassko\Test\UnitTestsGenerator\OutputNamespaceResolver;
use Kassko\Test\UnitTestsGenerator\PhpGenerator;
use Kassko\Test\UnitTestsGenerator\TestCreator;
use Kassko\Test\UnitTestsGenerator\TestDumper;
use Kassko\Test\UnitTestsGenerator\TestGenerator;
use Kassko\Test\UnitTestsGenerator\Util\ClassNameParser;
use Kassko\Test\UnitTestsGenerator\Util\PhpElementsExtractor;
use Kassko\Test\UnitTestsGenerator\Util\Reflector;
use Symfony\Component\Filesystem\Filesystem;

class GenerateTestsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $classNameParser = new ClassNameParser;
        $reflector = new Reflector($classNameParser);
        $faker = new Faker;

        $this->generateTestsCommand = new GenerateTestsCommand(
            [],
            new FilesFinder(['input_files_locations' => [__DIR__ . '/Fixtures/']]),
            new PhpElementsExtractor,
            new TestCreator(
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
            new TestDumper(new Filesystem),
            new OutputDirectoryResolver([
                'files' => [
                    'map' => [
                        __DIR__ . '\\Fixtures\\' => __DIR__ . '\\FixturesTests\\',
                        //$dir . '\\src\\' => $dir . '\\tests\\auto\\'
                    ],
                    //'unique' => __DIR__ . '\\FixturesTests\\',
                ],
            ])
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
