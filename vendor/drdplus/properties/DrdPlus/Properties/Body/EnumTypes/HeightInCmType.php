<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Body\EnumTypes;

use Doctrineum\Float\FloatEnumType;

class HeightInCmType extends FloatEnumType
{
    /**
     * should be the same as @see \DrdPlus\Codes\Properties\PropertyCode::HEIGHT_IN_CM
     * and can not be just linked to give direct return value and provide PhpStorm to-definition link support
     */
    const HEIGHT_IN_CM = 'height_in_cm';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HEIGHT_IN_CM;
    }
}
