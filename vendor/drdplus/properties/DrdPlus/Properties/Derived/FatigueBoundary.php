<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Derived\Partials\AbstractDerivedProperty;
use DrdPlus\Tables\Measurements\Fatigue\FatigueBonus;
use DrdPlus\Tables\Tables;

class FatigueBoundary extends AbstractDerivedProperty
{
    /**
     * @param Endurance $endurance
     * @param Tables $tables
     * @return FatigueBoundary
     */
    public static function getIt(Endurance $endurance, Tables $tables): FatigueBoundary
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            $tables->getFatigueTable()->toFatigue(
                new FatigueBonus(
                    $endurance->getValue() + 10,
                    $tables->getFatigueTable()
                )
            )->getValue()
        );
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::FATIGUE_BOUNDARY);
    }
}