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
    public $val;

    public function __construct(array $data)
    {
        $this->val = current($data);
    }
}
