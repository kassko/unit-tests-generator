<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\MockBehaviour as UtBe;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression as UtExpr;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\SpyKind as UtSpyKind;

class Service
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
     *  @Ut\Expectation(return=true, spies=@Ut\Spies({"gender_once"}), mocks=@Ut\Mocks({"rich", "woman"})),
     *  @Ut\Expectation(return=false, mocks=@Ut\Mocks({"rich", "man"})),
     *  @Ut\Expectation(return=false, mocks=@Ut\Mocks({"poor", "woman"})),
     *  @Ut\Expectation(return=false, mocks=@Ut\Mocks({"poor", "man"})),
     *  @Ut\Expectation(spies=@Ut\Spies({"unknown_exception"}), mocks=@Ut\Mocks({"unknown_gender"}))
     * })
     *
     * @Ut\MocksStore({
     *  @Ut\Mock(id="rich", expr=@UtExpr\Method(obj="richService", func="isRich"), behav=@UtBe\RetVal(true)),
     *  @Ut\Mock(id="poor", expr=@UtExpr\OppositeMockOf("rich")),
     *  @Ut\Mock(id="woman", expr=@UtExpr\Method(obj="genderService", member=false, func="getGender"), return="F"),
     *  @Ut\Mock(id="man", expr=@UtExpr\Method(obj="genderService", func="getGender"), return="M"),
     *  @Ut\Mock(id="unknown_gender", expr=@UtExpr\Method(obj="genderService", func="getGender"), return="R")
     * })
     *
     * @Ut\SpiesStore({
     *  @Ut\Spy(id="gender_once", expected=@UtSpyKind\Calls(nr=1, method=@UtExpr\Method(obj="genderService", member=false, func="getGender"))),
     *  @Ut\Spy(id="unknown_exception", expected=@UtSpyKind\Exception_(class="MyException", code=1))
     * })
     */
    public function isRichWoman(\GenderService $genderService)
    {
        $gender = $genderService->getGender();
        if ('F' !== $gender && 'M' !== $gender) {
            throw new \MyException('Unkown gender', 1);
        }

        return true === $this->richService->isRich() && 'F' === $gender;
    }
}
