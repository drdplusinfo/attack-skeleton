<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Body;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Properties\HeightInterface;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

/**
 * In fact bonus of a distance, @see \DrdPlus\Tables\Measurements\Distance\DistanceBonus
 */
class Height extends StrictObject implements BodyProperty, IntegerInterface, HeightInterface
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param HeightInCm $heightInCm
     * @param Tables $tables
     * @return Height
     */
    public static function getIt(HeightInCm $heightInCm, Tables $tables): Height
    {
        return new static($heightInCm, $tables);
    }

    /**
     * @param HeightInCm $heightInCm
     * @param Tables $tables
     */
    private function __construct(HeightInCm $heightInCm, Tables $tables)
    {
        $heightInMeters = $heightInCm->getValue() / 100;
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $distance = new Distance($heightInMeters, DistanceUnitCode::METER, $tables->getDistanceTable());
        // height is bonus of distance in fact
        $this->value = $distance->getBonus()->getValue();
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::HEIGHT);
    }

    /**
     * It is bonus of distance in fact
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

}