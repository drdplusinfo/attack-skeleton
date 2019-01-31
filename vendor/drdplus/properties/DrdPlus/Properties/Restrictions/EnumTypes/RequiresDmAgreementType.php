<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Restrictions\EnumTypes;

use Doctrineum\Boolean\BooleanEnumType;
use DrdPlus\Codes\Properties\PropertyCode;

class RequiresDmAgreementType extends BooleanEnumType
{
    const REQUIRES_DM_AGREEMENT = PropertyCode::REQUIRES_DM_AGREEMENT;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::REQUIRES_DM_AGREEMENT;
    }
}