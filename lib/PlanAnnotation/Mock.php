<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Mock
{
    /**
     * @var string
     */
    public $id;
    /**
     * Expression
     */
    public $expr;
    /**
     * @var mixed
     */
    public $behav;
    /**
     * @var mixed
     */
    public $return;
    /**
     * @var bool
     */
    public $enabled = true;
}