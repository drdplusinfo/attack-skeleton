<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\RemarkableSenses;

use DrdPlus\Codes\Properties\PropertyCode;

class Smell extends RemarkableSenseProperty
{

    /**
     * @return Smell|RemarkableSenseProperty
     */
    public static function getIt(): Smell
    {
        return static::getEnum(PropertyCode::SMELL);
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::SMELL);
    }

}