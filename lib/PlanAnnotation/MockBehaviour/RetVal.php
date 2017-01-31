<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\Behaviour;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class RetVal implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Behaviour
{
    /**
     * @var mixed
     */
    public $val;

    public function __construct(array $data)
    {
        $this->val = current($data);
    }
}
