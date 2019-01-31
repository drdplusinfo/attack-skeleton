<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived;

use DrdPlus\Properties\Property;
use Granam\Integer\IntegerInterface;

interface DerivedProperty extends Property, IntegerInterface
{

}