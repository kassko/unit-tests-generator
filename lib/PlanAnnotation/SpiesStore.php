<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"METHOD"})
*
* @author kko
*/
final class SpiesStore
{
    /**
     * One or more Mock annotations.
     *
     * @var mixed \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Spy[]
     */
    public $items = [];
}
