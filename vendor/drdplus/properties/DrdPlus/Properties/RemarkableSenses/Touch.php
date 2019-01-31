<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\RemarkableSenses;

use DrdPlus\Codes\Properties\PropertyCode;

class Touch extends RemarkableSenseProperty
{

    /**
     * @return Touch|RemarkableSenseProperty
     */
    public static function getIt(): Touch
    {
        return static::getEnum(PropertyCode::TOUCH);
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::TOUCH);
    }
}