<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Mock;

use Kassko\Test\UnitTestsGenerator\PlanModel\AbstractMock;

/**
 * Mocks
 */
class Mocks extends AbstractMock
{
    /**
     * @var Mock[]
     */ 
    private $cases;

    /**
     * @param string    $id
     * @param Mock[]   $cases (default)
     * @param bool      $enabled (default)
     */
    public function __construct($id, array $cases = [], $enabled = true)
    {
        parent::__construct($id, $enabled);

        $this->cases = $cases;
    }   
    
    /**
     * @param Mock $case
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
     * @return Mock
     */
    public function getCase($id)
    {
        return $this->cases[$id];
    }

    /**
     * @return Mock[]
     */
    public function getCases()
    {
        return $this->cases;
    }
}
