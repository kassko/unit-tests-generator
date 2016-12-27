<?php

namespace Kassko\Test\UnitTestsGenerator;

use RuntimeException;

/**
 * OutputDirectoryResolvingException
 */
class OutputDirectoryResolvingException extends RuntimeException
{
    /**
     * @param string $inputFile
     *
     * @return OutputDirectoryResolvingException
     */
    public static function cannotResolve($inputFile)
    {
        return new self(sprintf('Cannot resolve output test directory for file "%s".', $inputFile));
    }

    /**
     * @param array     $config
     * @param string    $inputFile
     *
     * @return OutputDirectoryResolvingException
     */
    public static function badConfiguration(array $config, $inputFile)
    {
        return new self(
            sprintf(
                'Invalid configuration encountered during resolving output test directory for file "%s".'
                . ' The given config schema (the set of keys) is "%s".',
                $inputFile,
                json_encode($this->getConfigKeys($config))
            )
        );
    }

    /**
     * @param array     $config
     * @param string    $depthName (optional)
     *
     * @return OutputDirectoryResolvingException
     */
    protected function getConfigKeys(array $config, $depthName = 'root')
    {
        $keys = [];

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $keys[$depthName] = $this->getConfigKeys($value, $key);
            } else {
                $keys[$depthName] = $key;
            }
        }

        return $keys;
    }
}
