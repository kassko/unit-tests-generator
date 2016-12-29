<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

use Kassko\Test\UnitTestsGeneratorTest\Fixtures\Address;

class Person
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var integer
     */
    private $age;
    /**
     * @var Address
     */
    private $address;

    /**
     * @param string $name
     * @param integer $age
     * @param Address $address
     */
    public function __construct($name, $age, Address $address)
    {
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param integer $age
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->age;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }
}
