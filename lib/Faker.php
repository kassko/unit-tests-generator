<?php

namespace Kassko\Test\UnitTestsGenerator;

/**
 * Faker
 */
class Faker
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function generateValueFromType($type)
    {
        return $this->{'generate' . ucfirst($type)}();
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
    public function generateInteger()
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
     * @return array
     */
    public function generateArray()
    {
        return ['\'foo\'', '\'bar\''];
    }
}
