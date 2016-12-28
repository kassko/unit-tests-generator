<?php

namespace Kassko\Test\UnitTestsGeneratorTest\Fixtures;

use Kassko\Test\UnitTestsGenerator\PlanAnnotation as Ut;
use Kassko\Test\UnitTestsGenerator\PlanAnnotation\Expression as UtExpr;

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
     * @Ut\CasesStore({
     *  @Ut\Case_(id="rich", expr=@UtExpr\Method(obj="richService", func="isRich"), value=true),
     *  @Ut\Case_(id="poor", expr=@UtExpr\NotCase("rich"))
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
