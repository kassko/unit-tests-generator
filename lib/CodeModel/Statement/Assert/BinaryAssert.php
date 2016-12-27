<?php

namespace Kassko\Test\UnitTestsGenerator\CodeModel\Statement\Assert;

use Kassko\Test\UnitTestsGenerator\CodeModel\Statement\AbstractAssert;
use Kassko\Test\UnitTestsGenerator\CodeModel\Value;

/**
 * BinaryAssert
 *
 * @method string isEquals()
 *
 *
 * @method string makeEquals()
 */
class BinaryAssert extends AbstractAssert
{
    /**
     * @var Value
     */
    private $leftOperand;
    /**
     * @var Value
     */
    private $rightOperand;

    /**
     * @param Value    $leftOperand
     * @param Value    $rightOperand
     * @param integer           $operation
     */
    public function __construct(Value $leftOperand, Value $rightOperand, $operation)
    {
        parent::__construct($operation);

        $this->leftOperand =  $leftOperand;
        $this->rightOperand = $rightOperand;
    }

    /**
     * @param Value $leftOperand
     *
     * @return self
     */
    public function setLeftOperand(Value $leftOperand)
    {
        $this->leftOperand = $leftOperand;

        return $this;
    }

    /**
     * @return Value
     */
    public function getLeftOperand()
    {
        return $this->leftOperand;
    }

    /**
     * @param Value $rightOperand
     *
     * @return self
     */
    public function setRightOperand(Value $rightOperand)
    {
        $this->rightOperand = $rightOperand;

        return $this;
    }

    /**
     * @return Value
     */
    public function getRightOperand()
    {
        return $this->rightOperand;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsOperation($operation)
    {
        return in_array($operation, ['equals']);
    }
}
