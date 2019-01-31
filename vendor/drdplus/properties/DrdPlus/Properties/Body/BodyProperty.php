<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Body;

use DrdPlus\Properties\Property;
use Granam\Number\NumberInterface;

/**
 * Just a tag interface
 */
interface BodyProperty extends Property, NumberInterface
{

}