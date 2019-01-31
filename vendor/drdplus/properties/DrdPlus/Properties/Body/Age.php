<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Partials\AbstractIntegerProperty;

/**
 * @method static Age getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Age add(int | \Granam\Integer\IntegerInterface $value)
 * @method Age sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Age extends AbstractIntegerProperty implements BodyProperty
{
    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::AGE);
    }

}