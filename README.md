Unit tests Generator
==================

NOT FULLY READY - WORK IN PROGRESS

This library generates for you corresponding unit tests of your code.

## Installation

You can install this library with composer.

```php
composer require kassko/unit-tests-generator:master
```

## Usage

### Generate basics tests

Given the following classes

```php
namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

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
     * @param string $name
     * @param integer $age
     * @param Address $address
     */
    public function __construct($name, $age, $address)
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
}
```

```php
<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

class Address
{
}
```

The library generate the following basics tests

```php
<?php

namespace Kassko\Test\UnitTestsGeneratorTestTest\Fixtures;

use Kassko\Test\UnitTestsGeneratorTest\Fixtures\Address;
use Kassko\Test\UnitTestsGeneratorTest\Fixtures\Person;
use Kassko\Util\MemberAccessor\ObjectMemberAccessor;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->objectMemberAccessor = new ObjectMemberAccessor;
        $address = $this->getMockBuilder(Address::class)->disableOriginalConstructor()->getMock();
        $this->person = new Person('foo', 1, $address);
    }

    /**
     * @test
     */
    public function constructor()
    {
        $address = $this->getMockBuilder(Address::class)->disableOriginalConstructor()->getMock();
        $this->person = new Person('foo', 1, $address);
        $this->assertEquals('foo', $this->objectMemberAccessor->getPropertyValue($this->person, 'name'));
        $this->assertEquals(1, $this->objectMemberAccessor->getPropertyValue($this->person, 'age'));
        $this->assertEquals($address, $this->objectMemberAccessor->getPropertyValue($this->person, 'address'));
    }

    /**
     * @test
     */
    public function getName()
    {
        $this->objectMemberAccessor->setPropertyValue($this->person, 'name', 'foo');
        $this->assertEquals('foo', $this->person->getName());
    }

    /**
     * @test
     */
    public function getAge()
    {
        $this->objectMemberAccessor->setPropertyValue($this->person, 'age', 1);
        $this->assertEquals(1, $this->person->getAge());
    }

    /**
     * @test
     */
    public function setName()
    {
        $this->assertEquals($this->person, $this->person->setName('foo'));
        $this->assertEquals('foo', $this->objectMemberAccessor->getPropertyValue($this->person, 'name'));
    }

    /**
     * @test
     */
    public function setAge()
    {
        $this->person->setAge(1);
        $this->assertEquals(1, $this->objectMemberAccessor->getPropertyValue($this->person, 'age'));
    }
}
```

```php
<?php

namespace Kassko\Test\UnitTestsGeneratorTestTest\Fixtures;

use Kassko\Test\UnitTestsGeneratorTest\Fixtures\Address;
use Kassko\Util\MemberAccessor\ObjectMemberAccessor;

class AddressTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->objectMemberAccessor = new ObjectMemberAccessor;
        $this->address = new Address;
    }

    /**
     * @test
     */
    public function getStreet()
    {
        $this->objectMemberAccessor->setPropertyValue($this->address, 'street', 'foo');
        $this->assertEquals('foo', $this->address->getStreet());
    }

    /**
     * @test
     */
    public function setStreet()
    {
        $this->assertEquals($this->address, $this->address->setStreet('foo'));
        $this->assertEquals('foo', $this->objectMemberAccessor->getPropertyValue($this->address, 'street'));
    }
}
```

### Generate more complex tests depending on your expectations

```php
<?php

use Kassko\Test\UnitTestsGenerator\Annotation as Ut;
use MyException;

class Manager
{
    /**
     * @var Service
     */
    private $richService;

    public function __construct($richService)
    {
        $this->richService = $richService;
    }

    /**
     * @Ut\Expectations({
     *  @Ut\Expectation(expect=@Value(true), path=@Path({"rich", "woman"})),
     *  @Ut\Expectation(expect=@Value(false), path=@Path({"rich", "man"})),
     *  @Ut\Expectation(expect=@Value(false), path=@Path({"poor", "woman"})),
     *  @Ut\Expectation(expect=@Value(false), path=@Path({"poor", "man"})),
     *  @Ut\Expectation(expect=@Exception(class='MyException', code=1), path=@Path({"unknown_gender"}))
     * })
     *
     * @Ut\CasesStore({
     *  @Ut\Case(id="rich", expr=@Ut\Method(prop="richService", func="isRich"), return=@Value(true)),
     *  @Ut\Case(id="poor", expr=@Ut\NotCase(id="rich")),
     *  @Ut\Case(id="woman", expr=@Ut\Method(var="genderService", func="getGender"), return=@Value("F")),
     *  @Ut\Case(id="man", expr=@Ut\Method(var="genderService", func="getGender"), return=@Value("M")),
     *  @Ut\Case(id="unknown_gender", expr=@Ut\Method(var="genderService", func="getGender"), return=@Ut\Value("R"))
     * })
     */
    public function isRichWoman($genderService)
    {
        if ('F' === $genderService->getGender() && 'M' === $genderService->getGender()) {
            throw new MyException('Unkown gender', 1);
        }

        return true === $this->richService->isRich() && 'F' === $genderService->getGender();
    }
}
```
Will generate tests corresponding to your expectations and so handle itself the stubs dependencies creation which is a hard work in tests creation.
