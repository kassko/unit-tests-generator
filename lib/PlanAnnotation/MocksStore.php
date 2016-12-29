<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"METHOD"})
*
* @author kko
*/
final class MocksStore
{
    /**
     * One or more Mock annotations.
     *
     * @var array<\Kassko\Test\UnitTestsGenerator\PlanAnnotation\Mock>
     */
    public $items = [];
}