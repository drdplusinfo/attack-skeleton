<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Restrictions;

use Doctrineum\Boolean\BooleanEnum;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Partials\WithHistoryTrait;
use Granam\Boolean\BooleanInterface;

class RequiresDmAgreement extends BooleanEnum implements RestrictionProperty
{
    use WithHistoryTrait;

    /**
     * @param bool $value
     * @return RequiresDmAgreement
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public static function getIt($value)
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

    /**
     * @return PropertyCode
     */
    public function getCode()
    {
        return PropertyCode::getIt(PropertyCode::REQUIRES_DM_AGREEMENT);
    }

}