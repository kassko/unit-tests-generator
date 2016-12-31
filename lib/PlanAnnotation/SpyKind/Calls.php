<?php

namespace Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression\Method;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 *
 * @author kko
 */
final class Calls implements \Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind
{
    /**
     * @var int
     */
    public $nr;
    /**
     * @var \Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression\Method
     */
    public $method;
}
