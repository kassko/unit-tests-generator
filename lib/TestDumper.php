<?php

namespace Kassko\Test\UnitTestsGenerator;

use Symfony\Component\Filesystem\Filesystem;

/**
 * TestDumper
 */
class TestDumper
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $testCode
     * @param string $outputFilePath
     *
     * [
     * 'code',
     * 'dest',
     * ]
     */
    public function dumpTestCode($testCode, $outputFilePath)
    {
        $this->filesystem->mkdir(dirname($outputFilePath), 0777);
        $this->filesystem->dumpFile($outputFilePath, $testCode);
    }
}
