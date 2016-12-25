<?php

namespace Kassko\Test\UnitTestsGenerator;

use Symfony\Component\Finder\Finder;

/**
 * FilesFinder
 */
class FilesFinder
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function findFiles()
    {
        $files = [];

        $finder = new Finder();
        $iterator = $finder
            ->files()
            ->in($this->config['input_files_locations']);

        foreach ($iterator as $file) {
            $files[] = $file->getRealpath();
        }

        return $files;
    }
}
