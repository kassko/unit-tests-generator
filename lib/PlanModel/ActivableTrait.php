<?php

namespace Kassko\Test\UnitTestsGenerator\PlanModel;

trait ActivableTrait
{
    /**
     * @var bool
     */
    private $activated = true;

    /**
     * @return bool
     */
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * @param bool (default)
     *
     * @return $this
     */
    public function activate($activated = true)
    {
        $this->activated = $activated;

        return $this;
    }
}
