<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"METHOD"})
*
* @author kko
*/
final class CasesStore
{
    /**
     * One or more Case_ annotations.
     *
     * @var array<\Kassko\Test\UnitTestsGenerator\PlanAnnotation\Case_>
     */
    public $items = [];
}