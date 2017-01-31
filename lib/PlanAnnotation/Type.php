<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"PROPERTY"})
*
* @author kko
*/
final class Type
{
    /**
     * @var string
     */
    public $value;
    /**
     * @var bool
     */
    public $activated = true;
}
