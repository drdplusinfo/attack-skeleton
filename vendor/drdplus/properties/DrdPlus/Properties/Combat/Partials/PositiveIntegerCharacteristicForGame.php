<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Combat\Partials;

use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\ToInteger;

abstract class PositiveIntegerCharacteristicForGame extends CharacteristicForGame implements PositiveInteger
{
    /**
     * @param int|string|float|PositiveInteger $value
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative
     */
    protected function sanitizeValue($value): int
    {
        return ToInteger::toPositiveInteger($value);
    }
}