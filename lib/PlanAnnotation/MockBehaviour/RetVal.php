<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class RetVal implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour
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
