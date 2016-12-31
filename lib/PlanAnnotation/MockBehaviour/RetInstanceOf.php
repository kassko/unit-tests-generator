<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 *
 * @author kko
 */
final class RetInstanceOf implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour
{
    /**
     * @var string
     */
    public $class;

    public function __construct(array $data)
    {
        $this->class = current($data);
    }
}
