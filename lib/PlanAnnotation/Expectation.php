<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Expectation
{
    /**
     * @var mixed
     */
    public $expected;
    /**
     * @var array<\Kassko\Test\UnitTestsGenerator\PlanAnnotation\Path>
     */
    public $path;
    /**
     * @var bool
     */
    public $enabled = true;
}