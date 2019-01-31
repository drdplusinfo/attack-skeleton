<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Derived\Partials\AbstractDerivedProperty;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Tables;

class WoundBoundary extends AbstractDerivedProperty
{
    /**
     * @param Toughness $toughness
     * @param Tables $tables
     * @return WoundBoundary
     */
    public static function getIt(Toughness $toughness, Tables $tables): WoundBoundary
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static(
            $tables->getWoundsTable()->toWounds(
                new WoundsBonus(
                    $toughness->getValue() + 10,
                    $tables->getWoundsTable()
                )
            )->getValue()
        );
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::WOUND_BOUNDARY);
    }
}