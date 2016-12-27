<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel\Case;

/**
 * AbstractCase
 */
abstract class AbstractCase implements Case_
{
    /**
     * @var string
     */ 
    private $id;
    
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param string        $id
     * @param bool          $enabled (default)
     */
    public function __construct($id, $enabled = true)
    {
        $this->id = $id; 
        $this->enabled = $enabled;
    }   
    
    /**
     * @return string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enabled;
    }
}
