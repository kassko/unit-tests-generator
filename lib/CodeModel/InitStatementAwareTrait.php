<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel;

use LogicException;

/**
 * InitStatementAwareTrait
 */
trait InitStatementAwareTrait
{
    /**
     * @var Statement[]
     */
    private $initStatements = [];

    /**
     * @param string        $id
     * @param Statement     $initStatement
     * @param boolean       $shouldNotExist (default)
     *
     * @return $this
     */
    public function addInitStatement($id, Statement $initStatement, $shouldNotExist = true)
    {
        if ($shouldNotExist && isset($this->initStatements[$id])) {
            throw new LogicException(sprintf('A statement with id "%s" already exists.', $id));
        }

        $this->initStatements[$id] = $initStatement;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return Statement
     */
    public function getInitStatement($id)
    {
        if (!isset($this->initStatements[$id])) {
            throw new LogicException(sprintf('There is not instantiation with id "%s".', $id));
        }

        return $this->initStatements[$id];
    }

    /**
     * @return Statement[]
     */
    public function getInitStatements()
    {
        return $this->initStatements;
    }

    /**
     * @return Statement[]
     */
    public function getOrderedInitStatements()
    {
        return array_reverse($this->initStatements);
    }
}
