<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Exception_ implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind
{
    /**
     * @var string
     */
    public $class;
    /**
     * @var integer
     */
    public $code;
    /**
     * @var string
     */
    public $message;
}
