<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Statement;

use DomainException;

/**
 * AbstractAssert
 */
abstract class AbstractAssert implements Assert_
{
    /**
     * @var string
     */
    protected $operation;

    /**
     * @param string $operation
     */
    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param string $method
     * @param string $arguments
     *
     * @return boolean|null Returns boolean for issers and null for "make" methods.
     */
    public function __call($method, array $arguments)
    {
        /**
         * @todo Create a white list of methods and check method invoked is in this white list.
         */

        $biggestPrefix = substr($method, 0, 4);
        switch (true) {
            case 'make' === $biggestPrefix:
                if (!$this->supportsOperation($operation = lcfirst(substr($method, 4)))) {
                    throw new DomainException(sprintf('The class "%s" do not support operation "%s".', get_called_class(), $operation));
                }
                $this->operation = $operation;

                break;
            case 'is' === substr($biggestPrefix, 0, 2):
                if (!$this->supportsOperation($operation = lcfirst(substr($method, 2)))) {
                    return false;
                }

                return $this->operation === $operation;
        }
    }

    /**
     * @param string $operation
     *
     * @return bool
     */
    abstract protected function supportsOperation($operation);
}
