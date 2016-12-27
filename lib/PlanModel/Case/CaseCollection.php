<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Case;

use Kassko\Test\UnitTestsGenerator\PlanModel\AbstractCase;

/**
 * CaseCollection
 */
class CaseCollection extends AbstractCase
{
    /**
     * @var Case_[]
     */ 
    private $cases;

    /**
     * @param string    $id
     * @param Case_[]   $cases (default)
     * @param bool      $enabled (default)
     */
    public function __construct($id, array $cases = [], $enabled = true)
    {
        parent::__construct($id, $enabled);

        $this->cases = $cases;
    }   
    
    /**
     * @param Case_ $case
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
     * @return Case_
     */
    public function getCase($id)
    {
        return $this->cases[$id];
    }

    /**
     * @return Case_[]
     */
    public function getCases()
    {
        return $this->cases;
    }
}
