<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Native;

use Doctrineum\Boolean\BooleanEnum;
use DrdPlus\Properties\Partials\WithHistoryTrait;
use DrdPlus\Properties\Property;
use Granam\Boolean\BooleanInterface;

abstract class NativeProperty extends BooleanEnum implements Property
{
    use WithHistoryTrait;

    /**
     * @param bool|BooleanInterface $value
     * @return NativeProperty
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public static function getIt($value): NativeProperty
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static($value);
    }

    /**
     * @param bool|BooleanInterface $enumValue
     * @throws \Doctrineum\Scalar\Exceptions\UnexpectedValueToEnum
     */
    protected function __construct($enumValue)
    {
        parent::__construct($enumValue);
        $this->noticeChange();
    }
}