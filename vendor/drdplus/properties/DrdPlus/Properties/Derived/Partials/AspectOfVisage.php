<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived\Partials;

use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Calculations\SumAndRound;
use Granam\Integer\IntegerInterface;

/** @noinspection SingletonFactoryPatternViolationInspection */
abstract class AspectOfVisage extends AbstractDerivedProperty
{
    /**
     * @param IntegerInterface $firstProperty
     * @param IntegerInterface $secondProperty
     * @param Charisma $charisma
     */
    protected function __construct(IntegerInterface $firstProperty, IntegerInterface $secondProperty, Charisma $charisma)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct(
            SumAndRound::round(($firstProperty->getValue() + $secondProperty->getValue()) / 2 + $charisma->getValue() / 2)
        );
    }
}