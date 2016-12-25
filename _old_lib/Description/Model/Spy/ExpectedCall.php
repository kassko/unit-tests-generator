<?php

namespace Kassko\UnitTest\Generator\Description\Model\Spy;

use Kassko\UnitTest\Generator\Description\Model\SpyInterface;

class ExpectedCall implements SpyInterface
{
    /**
     * @var string
     */
    private $collaboratorId;
    /**
     * @var string
     */
    private $methodName;
    /**
     * @var integer
     */
    private $count;
    /**
     * @var \Kassko\UnitTest\Generator\Description\Model\Spy\MatcherInterface[]
     */
    private $matchers;
}
