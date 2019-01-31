<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\RemarkableSenses;

use DrdPlus\Codes\Properties\PropertyCode;

class Hearing extends RemarkableSenseProperty
{

    /**
     * @return Hearing|RemarkableSenseProperty
     */
    public static function getIt(): Hearing
    {
        return static::getEnum(PropertyCode::HEARING);
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::HEARING);
    }

}