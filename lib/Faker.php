<?php

namespace Kassko\Test\UnitTestsGenerator;

/**
 * Faker
 */
class Faker
{
    /**
     * @param string $type
     * @param string $semanticType
     *
     * @return string
     *
     * @throws \DomainException
     */
    public function generateValueFromType($type, $semanticType = null)
    {
        if (null !== $semanticType) {
            return $this->generateString();//For the moment.
        } elseif ('object' !== $type) {
            switch ($type) {
                case 'string':
                case 'mixed':
                    return $this->generateString();
                case 'int':
                case 'integer':
                    return $this->generateInt();
                case 'bool':
                case 'boolean':
                    return $this->generateBool();
                case 'float':
                    return $this->generateFloat();
                case 'array':
                    return $this->generateArray();
                case 'resource':
                    return $this->generateString();//For the moment.
                case 'null':
                    return null;
                case 'mixed':
                    return $this->generateString();
                case 'number':
                    return $this->generateInt();
                case 'callback':
                    return function () {};
                case 'array|object':
                    return $this->generateArray();
                case 'void':
                    return null;
            }

            throw new \DomainException(sprintf('Invalid type "%s"', $type));
        } elseif ('DateTime' === $type) {
            return new \DateTime;
        }

        return $this->generateString();//For the moment.
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
