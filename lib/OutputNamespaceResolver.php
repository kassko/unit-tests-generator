<?php

namespace Kassko\Test\UnitTestsGenerator;

use DomainException;
use RuntimeException;

/**
 * OutputNamespaceResolver
 */
class OutputNamespaceResolver
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $namespace
     *
     * @return string $namespace
     *
     * @throws DomainException If no configuration was provided for path "namespaces".
     */
    public function resolveTestNamespace($namespace)
    {
        if (isset($this->config['namespaces']['psr4_prefix'])) {
            $nbLevelsPrefixNamespace = $this->config['namespaces']['psr4_prefix']['nb_levels'];
            $namespaceParts = explode('\\', $namespace);

            if ($nbLevelsPrefixNamespace >= $nbLevelsNamespace = count($namespaceParts)) {
                throw new RuntimeException(
                    sprintf(
                        'The namespace %s must contains more levels than the number levels configured for prefix namespaces.'
                        . ' \r\n\r\n The namespace "%s" contains %s levels.'
                        . ' \r\n And you configure a prefix namespace to contain "%d" levels.'
                        . ' \r\n The configuration is in path "namespaces.psr4_prefix.nb_levels".',
                        $namespace,
                        $namespace,
                        $nbLevelsNamespace,
                        $nbLevelsPrefixNamespace
                    )
                );
            }

            $namespaceParts[$nbLevelsPrefixNamespace-1] .= 'Test';

            return implode('\\', $namespaceParts);
        }

        throw new DomainException('Looks like configuration was not provided for path "namespaces".');
    }
}
