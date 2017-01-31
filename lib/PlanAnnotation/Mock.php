<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

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
     * @var mixed array<\Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression>
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
    public $activated = true;
}
