<?php

namespace Kassko\Test\UnitTestsGenerator;

use Symfony\Component\Filesystem\Filesystem;

/**
 * CodeDumper
 */
class CodeDumper
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
     * @param string $code
     * @param string $outputFilePath
     *
     * [
     * 'code',
     * 'dest',
     * ]
     */
    public function dumpCode($code, $outputFilePath)
    {
        $this->filesystem->mkdir(dirname($outputFilePath), 0777);
        $this->filesystem->dumpFile($outputFilePath, $code);
    }
}
