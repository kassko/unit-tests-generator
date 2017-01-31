<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Spy
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var mixed \Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind
     */
    public $expected;
    /**
     * @var bool
     */
    public $activated = true;
}
