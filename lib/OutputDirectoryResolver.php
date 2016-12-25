<?php

namespace Kassko\Test\UnitTestsGenerator;

use Kassko\Test\UnitTestsGenerator\Exception\OutputDirectoryResolvingException;

/**
 * OutputDirectoryResolver
 */
class OutputDirectoryResolver
{
    /**
     * @var array
     */
    private $config = [];
    /**
     * @var array
     */
    private $inputDirLengthes = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->computeInputDirLengthes();
    }

    /**
     * @param string $inputFile
     */
    public function resolveOutputFilePathFromInputFilePath($inputFile)
    {
        $outputDirectory = null;
        if (isset($this->config['files']['unique'])) {
            $outputDirectory = $this->config['files']['unique'];
        } elseif (isset($this->config['files']['map'])) {
            foreach ($this->config['files']['map'] as $inputDir => $outputDir) {
                if ($inputDir === substr($inputFile, 0, $this->inputDirLengthes[$inputDir])) {
                    $outputDirectory = $this->config['files']['map'][$inputDir];
                }
            }
        } else {
            throw OutputDirectoryResolvingException::badConfiguration($this->config, $inputFile);
        }

        if (null === $outputDirectory) {
            throw OutputDirectoryResolvingException::cannotResolve($inputFile);
        }

        return $outputDirectory;
    }

    /**
     * Cache input dir lenghtes not to recompute it several times.
     */
    protected function computeInputDirLengthes()
    {
        foreach ($this->config['files']['map'] as $inputDir => $outputDir) {
            $this->inputDirLengthes[$inputDir] = strlen($inputDir);
        }
    }
}
