<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Native;

use DrdPlus\Codes\Properties\PropertyCode;

/**
 * @method static NativeRegeneration getIt($value)
 */
class NativeRegeneration extends NativeProperty
{
    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::NATIVE_REGENERATION);
    }
}