<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

use Kassko\Test\UnitTestsGenerator\PlanModel\Case;

/**
 * Path
 */
class Path
{
    /**
     * @var Case[]
     */ 
    private $cases;

    /**
     * @param Case[] $cases (default)
     */
    public function __construct(array $cases = [])
    {
        $this->cases = $cases;
    }   
    
    /**
     * @param Case $case
     *
     * @return self
     */ 
    public function addCase(Case $case)
    {
        $this->cases[$case->getId()] = $case;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return Case
     */
    public function getCase($id)
    {
        return $this->case[$id];
    }

    /**
     * @return Case[]
     */
    public function getCases()
    {
        return $this->cases;
    }
}
