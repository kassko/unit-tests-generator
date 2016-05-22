Unit tests Generator - NOT READY - NOT STABLE AT ALL - WORK IN PROGRESS
==================

NOT READY - NOT STABLE AT ALL - WORK IN PROGRESS

This component allows to access to non public member and to execute non public methods.

## Installation

You can install this component with composer.

```php
composer require 'kassko-php/unit-tests-generator:master'
```

## Usage

### Example 1: usage on tests that test the result are good

Given the class:
```php
class SomeClass
{
    public function formatData($data)
    {
        if (!empty($data)) {
            foreach ($data as &$item) {
                $item *= 2;
            }

            return $data;
        }

        return [];
    }
}
```

And the yaml test description:
```yaml
testsuite:
    testsuiteA: 
        class: 'Kassko\SampleTest' # The namespace for the test class to generate
        tests:
            formatData:
                sut:
                    instances:
                        dao_instance:
                            class: Dao
                    execution:
                        instance: dao_instance
                        method: formatData
                        param_serie:
                            serie_a:
                                - [123]
                                - 'foo'
                            serie_b:
                                - []
                                - 'foo'
                            serie_c:
                                - null
                                - 'foo'
                        result_serie:
                            serie_a:
                                - [246]
                                - 'paramIndex(2)'
                            serie_b:
                                - []
                                - 'paramIndex(2)'
                            serie_c:
                                - []
                                - 'paramIndex(2)'

                        # Or if only one serie
                        # param:
                        #    - [123]
                        #    - 'foo'
                        # result_serie:
                        #    - [246]
                        #    - 'paramIndex(2)'
```

And the resulting phpunit code
```php
//To complete
```

### Example 2: usage on tests that test behaviour

Given the class:
```php
class SomeClass
{
    /**
     * @var SomeDependencyClass
     */
    private $dependency;

    public function getData($paramA, $paramB, $paramC)
    {
        return $this->connection
            ->execute(
                'foo',
                [
                    'paramA' => $paramA,
                    'paramB' => $paramB,
                    'paramC' => $paramC,
                ],
                new SomeClass
            )
        ;
    }
}
```

And the yaml test description:
```yaml
testsuite:
    testsuiteA: 
        class: 'Kassko\SampleTest' # The namespace for the test class to generate
        tests:
            getResult:
                sut:
                    instances:
                        dao_instance:
                            class: Dao
                            attributes:
                                connection: connection_stub
                    execution:
                        instance: dao_instance
                        method: getResult
                        param:
                            - 'foo'
                            - 'bar'
                            - false

                        # you can specify indexes
                        # param:
                        #     param_a: 'foo'
                        #     param_b: 'bar'
                        #     param_c: false

                collaborators:
                    connection_stub:
                        type: stub
                        class: Connection

                spies:
                    spy_one:
                        type: expected_call
                        collaborator: connection_stub
                        method: getData
                        count: 1
                        matchers:
                            - foo
                            - ['paramIndex(1)', 'paramIndex(2)', 'paramIndex(3)'] # Or [paramName("param_a"), paramName("param_b"), paramName("param_c")]
                            - 'instanceOfClass("SomeClass")' # Expression language

                        # type: expected_exception # This type exists too.
```

And the resulting phpunit code
```php
//To complete
```

### Example 3: usage more complex with test that test result and behaviour on an object

Given the class:
```php
public function getResult($paramA, $paramB, $paramC)
{
    $data = $this->connection
        ->execute(
            'foo',
            [
                'paramA' => $paramA,
                'paramB' => $paramB,
                'paramC' => $paramC,
            ],
            new SomeClass
        )
    ;

    return $this->hydrateData($data);
}
```

And the yaml test description:
```yaml
testsuite:
    testsuiteA: 
        class: 'Kassko\SampleTest' # The namespace for the test class to generate
        tests:
            getResult:
                sut:
                    instances:
                        dao_instance:
                            class: Dao
                            attributes:
                                connection: connection_stub
                    execution:
                        instance: dao_instance
                        method: getResult
                        param:
                            - 'foo'
                            - 'bar'
                            - false

                        # you can specify indexes
                        # param:
                        #     param_a: 'foo'
                        #     param_b: 'bar'
                        #     param_c: false

                collaborators:
                    dao_stub:
                        type: hybrid_stub 
                        instance: dao_instance
                    connection_stub:
                        type: stub
                        class: Connection
                        # You can specify a return value for method "execute" like below or do nothing and in the spy section use the expression language result. Hence a spy with a random return value will be created. See the spy section.
                        # method: execute
                            # return: 'foo'

                spies:
                    spy_one:
                        type: expected_call
                        collaborator: connection_stub
                        method: getData
                        count: 1
                        matchers:
                            - foo
                            - ['paramIndex(1)', 'paramIndex(2)', 'paramIndex(3)'] # Or [paramName("param_a"), paramName("param_b"), paramName("param_c")]
                            - 'instanceOfClass("SomeClass")' # Expression language
                    spy_two:
                        type: expected_call
                        collaborator: dao_mock
                        method: hydrateData
                        count: 1
                        matchers:
                            - 'result("connection", "callProcedure")' # Or 'foo'
```

And the resulting phpunit code
```php
//To complete
```
