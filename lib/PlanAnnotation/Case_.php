<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Case_
{
    /**
     * @var string
     */
    public $id;
    /**
     * Expression
     */
    public $expr = null;
    /**
     * @var mixed
     */
    public $value = null;
}