<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

class Address
{
	/**
     * @var string
     */
	private $street;

	/**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return self
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }
}
