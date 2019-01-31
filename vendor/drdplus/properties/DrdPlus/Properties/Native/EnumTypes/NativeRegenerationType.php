<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Native\EnumTypes;

use Doctrineum\Boolean\BooleanEnumType;

class NativeRegenerationType extends BooleanEnumType
{
    /**
     * should be the same as @see \DrdPlus\Codes\Properties\PropertyCode::NATIVE_REGENERATION
     * and can not be just linked to give direct return value and provide PhpStorm to-definition link support
     */
    const NATIVE_REGENERATION = 'native_regeneration';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NATIVE_REGENERATION;
    }
}
