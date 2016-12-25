<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\AbstractTestGenerator;
use Kassko\Test\UnitTestsGenerator\FilesFinder;
use Kassko\Test\UnitTestsGenerator\OutputDirectoryResolver;
use Kassko\Test\UnitTestsGenerator\TestCreator;
use Kassko\Test\UnitTestsGenerator\TestDumper;
use Kassko\Test\UnitTestsGenerator\Util\PhpElementsExtractor;

/**
 * GenerateTestsCommand
 */
class GenerateTestsCommand
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var FilesFinder
     */
    private $filesFinder;
    /**
     * @var PhpElementsExtractor
     */
    private $phpElementsExtractor;
    /**
     * @var TestCreator
     */
    private $testCreator;
    /**
     * @var AbstractTestGenerator
     */
    private $generator;
    /**
     * @var TestDumper
     */
    private $testDumper;
    /**
     * @var OutputDirectoryResolver
     */
    private $outputDirectoryResolver;

    /**
     * @param array                     $config
     * @param FilesFinder               $filesFinder
     * @param PhpElementsExtractor      $phpElementsExtractor
     * @param TestCreator               $testCreator
     * @param AbstractTestGenerator     $generator
     * @param TestDumper                $testDumper
     * @param OutputDirectoryResolver   $outputDirectoryResolver
     */
    public function __construct(
        array $config,
        FilesFinder $filesFinder,
        PhpElementsExtractor $phpElementsExtractor,
        TestCreator $testCreator,
        AbstractTestGenerator $generator,
        TestDumper $testDumper,
        OutputDirectoryResolver $outputDirectoryResolver
    ) {
        $this->config = $config;
        $this->filesFinder = $filesFinder;
        $this->phpElementsExtractor = $phpElementsExtractor;
        $this->testCreator = $testCreator;
        $this->generator = $generator;
        $this->testDumper = $testDumper;
        $this->outputDirectoryResolver = $outputDirectoryResolver;
    }

    public function process()
    {
        foreach ($this->filesFinder->findFiles() as $filePath) {
            require_once $filePath;

            $fullClasses = $this->phpElementsExtractor->extractClassesFromFile($filePath);
            foreach ($fullClasses as $fullClass) {
                $classModel = $this->testCreator->createTestForClass($fullClass);

                $testCode = $this->generator->generateCodeForTest($classModel);

                $outputFilePath =
                    $this->outputDirectoryResolver->resolveOutputFilePathFromInputFilePath($filePath)
                    . '/'
                    . basename($filePath, '.php')
                    . 'Test.php';
                $this->testDumper->dumpTestCode($testCode, $outputFilePath);
            }
        }
    }
}
