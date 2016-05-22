Unit tests Generator - NOT READY - NOT STABLE AT ALL - WORK IN PROGRESS
==================

This component allows to generate test code from a test description.

## Installation

You can install this component with composer.

```php
composer require kassko/php-unit-tests-generator:master
```

## Usage

Given:
```php
class SomeClass
{
    private $somePrivateProperty = 'some private property';

    private function somePrivateMethod()
    {
        return 'some private method';
    }

    private function somePrivateMethodWithParam($paramA, $paramB)
    {
        return 'some private method with param \"$paramA\" and \"$paramB\"';
    }

    private function somePrivateProcedureMethod($paramA, &$paramB)
    {
        $paramB = 'baz';
    }
}
```

Accessing SomeClass members:
```php
use Kassko\Component\MemberAccessor\ObjectMemberAccessor;

$someObject = new SomeClass;
$accessor = new ObjectMemberAccessor;

$fooPropertyValue = $accessor->getPropertyValue($someObject, 'somePrivateProperty');
echo $fooPropertyValue;//Display 'some private property'.

$accessor->setPropertyValue($someObject, 'somePrivateProperty', 'foo');
//Set the value 'foo' in $somePrivateProperty.

$fooMethodValue = $accessor->getMethodValue($someObject, 'somePrivateMethod');
echo $fooMethodValue;//Display 'some private method'.

$fooMethodValue = $accessor->getMethodValue($someObject, 'somePrivateMethodWithParam', ['foo', 'bar']);
echo $fooMethodValue;//Display 'some private method with param "foo" and "bar"'.

$bar = 'bar';
$params = ['foo', &$bar];
$accessor->executeMethod($someObject, 'somePrivateProcedureMethod', [&$params]);
echo $params[0];//Display "foo".
echo $params[1];//Display "baz".
```
