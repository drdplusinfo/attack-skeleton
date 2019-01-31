<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Partials;

use Doctrineum\Scalar\ScalarEnum;
use DrdPlus\Properties\Property;
use Granam\String\StringInterface;

/**
 * @method static AbstractFloatProperty getEnum(string $enumValue)
 */
abstract class AbstractStringProperty extends ScalarEnum implements Property, StringInterface
{
    public function getValue(): string
    {
        return parent::getValue();
    }

}