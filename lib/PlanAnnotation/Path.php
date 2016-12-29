<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation;

/**
* @Annotation
* @Target({"ANNOTATION"})
*
* @author kko
*/
final class Path
{
    /**
     * @var string
     */
    public $mocks;

    public function __construct(array $data)
    {
        $this->mocks = $data;
    }
}
