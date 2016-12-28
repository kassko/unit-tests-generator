<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 *
 * @author kko
 */
final class Method implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression
{
    /**
     * @var string
     */
    public $obj;
    /**
     * @var string
     */
    public $func;
    /**
     * @var bool
     * @required
     */
    public $member;
}
