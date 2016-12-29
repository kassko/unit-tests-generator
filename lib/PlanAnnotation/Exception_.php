<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Exception_
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
     * string
     */
    public $msg;
}