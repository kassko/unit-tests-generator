<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"METHOD"})
*
* @author kko
*/
final class Expectations
{
    /**
     * @var array<\Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expectation>
     */
    public $items = [];
}