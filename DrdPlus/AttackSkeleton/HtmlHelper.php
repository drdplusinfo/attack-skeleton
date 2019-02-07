<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton;

use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;

class HtmlHelper extends \DrdPlus\RulesSkeleton\HtmlHelper
{
    public const CLASS_INCREASED = 'increased';
    public const CLASS_DECREASED = 'decreased';

    /**
     * @param int|IntegerInterface $previous
     * @param int|IntegerInterface $current
     * @return string
     */
    public function getCssClassForChangedValue($previous, $current): string
    {
        if (ToInteger::toInteger($previous) < ToInteger::toInteger($current)) {
            return self::CLASS_INCREASED;
        }
        if (ToInteger::toInteger($previous) > ToInteger::toInteger($current)) {
            return self::CLASS_DECREASED;
        }

        return '';
    }
}