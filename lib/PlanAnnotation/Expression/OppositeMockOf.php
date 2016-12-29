<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 *
 * @author kko
 */
final class OppositeMockOf implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression
{
    /**
     * @var string
     */
    public $id;

    public function __construct(array $data)
    {
        $this->id = current($data);
    }
}
