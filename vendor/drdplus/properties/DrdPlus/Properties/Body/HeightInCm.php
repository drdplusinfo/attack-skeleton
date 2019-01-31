<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Partials\AbstractFloatProperty;
use Granam\Number\NumberInterface;

/**
 * @method static HeightInCm getIt(float | NumberInterface $value)
 */
class HeightInCm extends AbstractFloatProperty implements BodyProperty
{
    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::HEIGHT_IN_CM);
    }

}