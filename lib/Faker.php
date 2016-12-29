<?php

namespace Kassko\Test\UnitTestsGenerator;

use DomainException;

/**
 * Faker
 */
class Faker
{
    /**
     * @param string $type
     * @param string $fullClass
     *
     * @return string
     *
     * @throws DomainException
     */
    public function generateValueFromType($type, $fullClass)
    {
        if ($type !== 'object') {
            switch ($type) {
                case 'string':
                case 'mixed':
                    return $this->generateString();
                case 'int':
                case 'integer':
                    return $this->generateInt();
                case 'bool':
                    return $this->generateBool();
                case 'float':
                    return $this->generateFloat();
                case 'array':
                    return $this->generateArray();
            }

            throw new DomainException(sprintf('Invalid type "%s"', $type));
        }
            
        return $this->generateString();//For the moment.
        //throw new \LogicException(sprintf('Faker not implemented yet for class "%s"', $fullClass));
    }

    /**
     * @return string
     */
    public function generateString()
    {
        return '\'foo\'';
    }

    /**
     * @return int
     */
    public function generateInt()
    {
        return 1;
    }

    /**
     * @return bool
     */
    public function generateBool()
    {
        return false;
    }

    /**
     * @return float
     */
    public function generateFloat()
    {
        return 1.0;
    }

    /**
     * @return array
     */
    public function generateArray()
    {
        return ['\'foo\'', '\'bar\''];
    }
}
