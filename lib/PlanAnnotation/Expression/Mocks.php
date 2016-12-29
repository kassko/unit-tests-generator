<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Mocks implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression
{
    /**
     * @var string
     */
    public $items;

    public function __construct(array $data)
    {
        $this->items = $data;
    }
}
