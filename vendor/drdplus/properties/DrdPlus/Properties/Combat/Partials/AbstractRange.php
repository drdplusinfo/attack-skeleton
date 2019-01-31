<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Combat\Partials;

use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Tables;

abstract class AbstractRange extends CharacteristicForGame
{
    /**
     * @param Tables $tables
     * @return float
     */
    public function getInMeters(Tables $tables): float
    {
        // both encounter and maximal ranges are in fact distance bonuses
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return (new DistanceBonus($this->getValue(), $tables->getDistanceTable()))->getDistance()->getMeters();
    }
}