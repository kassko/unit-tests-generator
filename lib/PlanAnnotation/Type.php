<?php

namespace Kassko\DataMapper\Annotation;

/**
* @Annotation
* @Target({"PROPERTY"})
*
* @author kko
*/
final class Type implements Annotation
{
    /**
     * @var string
     */
    public $type;

    public function __construct(array $data)
    {
        $this->type = current($data);
    }
}
