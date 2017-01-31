<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Expectation
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var mixed
     */
    public $return;
    /**
     * @var \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Mocks
     */
    public $mocks;
    /**
     * @var \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Spies
     */
    public $spies;
    /**
     * @var bool
     */
    public $activated = true;
}
