<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;

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
     *
     * @Ut\Type(value="address")
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
        return $this->address;
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
