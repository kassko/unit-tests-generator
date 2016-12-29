<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\AbstractTestGenerator;
use Kassko\Test\UnitTestsGenerator\CodeDumper;
use Kassko\Test\UnitTestsGenerator\CodeModelCreator;
use Kassko\Test\UnitTestsGenerator\FilesFinder;
use Kassko\Test\UnitTestsGenerator\OutputDirectoryResolver;
use Kassko\Test\UnitTestsGenerator\Util\PhpElementsExtractor;
use Kassko\Test\UnitTestsGenerator\PlanLoader;

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
     * @var CodeModelCreator
     */
    private $codeModelCreator;
    /**
     * @var AbstractTestGenerator
     */
    private $generator;
    /**
     * @var CodeDumper
     */
    private $codeDumper;
    /**
     * @var OutputDirectoryResolver
     */
    private $outputDirectoryResolver;
    /**
     * @var PlanLoader
     */
    private $planLoader;

    /**
     * @param array                     $config
     * @param FilesFinder               $filesFinder
     * @param PhpElementsExtractor      $phpElementsExtractor
     * @param CodeModelCreator          $codeModelCreator
     * @param AbstractTestGenerator     $generator
     * @param CodeDumper                $codeDumper
     * @param OutputDirectoryResolver   $outputDirectoryResolver
     * @param PlanLoader                $planLoader
     */
    public function __construct(
        array $config,
        FilesFinder $filesFinder,
        PhpElementsExtractor $phpElementsExtractor,
        CodeModelCreator $codeModelCreator,
        AbstractTestGenerator $generator,
        CodeDumper $codeDumper,
        OutputDirectoryResolver $outputDirectoryResolver,
        PlanLoader $planLoader
    ) {
        $this->config = $config;
        $this->filesFinder = $filesFinder;
        $this->phpElementsExtractor = $phpElementsExtractor;
        $this->codeModelCreator = $codeModelCreator;
        $this->generator = $generator;
        $this->codeDumper = $codeDumper;
        $this->outputDirectoryResolver = $outputDirectoryResolver;
        $this->planLoader = $planLoader;
    }

    public function process()
    {
        foreach ($this->filesFinder->findFiles() as $filePath) {
            require_once $filePath;

            $this->phpElementsExtractor->parseFile($filePath);
            foreach ($this->phpElementsExtractor->getFullClasses() as $fullClass) {
                $classCodeModel = new CodeModel\Class_($fullClass);

                $this->codeModelCreator->loadCodeModel($classCodeModel);
                $classPlanModel = new PlanModel\Class_;
                $this->planLoader->load($classPlanModel, new PlanProviderResource('class', $fullClass));
                $this->codeModelCreator->completeCodeModelFromPlanModel($classCodeModel, $classPlanModel);

                $code = $this->generator->generateCodeFromCodeModel($classCodeModel);

                $outputFilePath =
                    $this->outputDirectoryResolver->resolveOutputFilePathFromInputFilePath($filePath)
                    . '/'
                    . basename($filePath, '.php')
                    . 'Test.php';
                $this->codeDumper->dumpCode($code, $outputFilePath);
            }
        }
    }
}
