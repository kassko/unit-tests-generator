<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\Behaviour;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 *
 * @author kko
 */
final class RetInstanceOf implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Behaviour
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
