<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression as UtExpr;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour as UtBehav;

class Manager
{
    /**
     * @var \RichService
     */
    private $richService;

    public function __construct($richService)
    {
        $this->richService = $richService;
    }

    /**
     * @Ut\Expectations({
     *  @Ut\Expectation(expected=true, path=@Ut\Path({"rich", "woman"})),
     *  @Ut\Expectation(expected=false, path=@Ut\Path({"rich", "man"})),
     *  @Ut\Expectation(expected=false, path=@Ut\Path({"poor", "woman"})),
     *  @Ut\Expectation(expected=false, path=@Ut\Path({"poor", "man"})),
     *  @Ut\Expectation(expected=@Ut\Exception_(class="MyException", code=1), path=@Ut\Path({"unknown_gender"}))
     * })
     *
     * @Ut\MocksStore({
     *  @Ut\Mock(id="rich", expr=@UtExpr\Method(obj="richService", func="isRich"), behav=@UtBehav\RetVal(true)),
     *  @Ut\Mock(id="poor", expr=@UtExpr\OppositeMockOf("rich")),
     *  @Ut\Mock(id="woman", expr=@UtExpr\Method(obj="genderService", func="getGender", member=false), return="F"),
     *  @Ut\Mock(id="man", expr=@UtExpr\Method(obj="genderService", func="getGender"), return="M"),
     *  @Ut\Mock(id="unknown_gender", expr=@UtExpr\Method(obj="genderService", func="getGender"), return="R")
     * })
     */
    public function isRichWoman(\GenderService $genderService)
    {
        if ('F' !== $genderService->getGender() && 'M' !== $genderService->getGender()) {
            throw new \MyException('Unkown gender', 1);
        }

        return true === $this->richService->isRich() && 'F' === $genderService->getGender();
    }
}
