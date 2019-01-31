<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Partials;

use Doctrineum\Float\FloatEnum;
use DrdPlus\Properties\Property;
use Granam\Float\FloatInterface;
use Granam\Number\NumberInterface;

/**
 * @method static AbstractFloatProperty getEnum(float|NumberInterface $enumValue)
 */
abstract class AbstractFloatProperty extends FloatEnum implements Property, FloatInterface
{

    /**
     * @param float|NumberInterface $value
     * @return AbstractFloatProperty
     */
    public static function getIt($value)
    {
        return static::getEnum($value);
    }

}