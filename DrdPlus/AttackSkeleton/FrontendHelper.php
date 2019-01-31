<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class FrontendHelper extends StrictObject
{
    public const MELEE_WEAPON = 'melee_weapon';
    public const RANGED_WEAPON = 'ranged_weapon';
    public const MELEE_WEAPON_HOLDING = 'melee_weapon_holding';
    public const RANGED_WEAPON_HOLDING = 'ranged_weapon_holding';
    public const SHIELD_HOLDING = 'shield_holding';
    public const SHIELD = 'shield';
    public const BODY_ARMOR = 'body_armor';
    public const HELM = 'helm';
    public const SCROLL_FROM_TOP = 'scroll_from_top';
    // special actions
    public const ACTION = 'action';
    public const ADD_NEW_MELEE_WEAPON = 'add_new_melee_weapon';
    public const ADD_NEW_RANGED_WEAPON = 'add_new_ranged_weapon';
    public const ADD_NEW_SHIELD = 'add_new_shield';
    public const ADD_NEW_BODY_ARMOR = 'add_new_body_armor';
    public const ADD_NEW_HELM = 'add_new_helm';
    /**
     * @var CurrentValues
     */
    private $currentValues;

    public function __construct(CurrentValues $currentValues)
    {
        $this->currentValues = $currentValues;
    }

    public function getScrollFromTop(): int
    {
        return (int)$this->currentValues->getSelectedValue(self::SCROLL_FROM_TOP);
    }

    /**
     * @param int|IntegerInterface $previous
     * @param int|IntegerInterface $current
     * @return string
     */
    public function getCssClassForChangedValue($previous, $current): string
    {
        if (ToInteger::toInteger($previous) < ToInteger::toInteger($current)) {
            return 'increased';
        }
        if (ToInteger::toInteger($previous) > ToInteger::toInteger($current)) {
            return 'decreased';
        }

        return '';
    }

    /**
     * @param array $additionalParameters
     * @return string
     */
    public function getLocalUrlWithQuery(array $additionalParameters = []): string
    {
        /** @var array $parameters */
        $parameters = $_GET;
        if ($additionalParameters) {
            foreach ($additionalParameters as $name => $value) {
                $parameters[$name] = $value;
            }
        }
        $queryParts = [];
        foreach ($parameters as $name => $value) {
            if (\is_array($value)) {
                /** @var array $value */
                foreach ($value as $index => $item) {
                    $queryParts[] = \urlencode("{$name}[{$index}]") . '=' . \urlencode((string)$item);
                }
            } else {
                $queryParts[] = \urlencode((string)$name) . '=' . \urlencode((string)$value);
            }
        }
        $query = '';
        if ($queryParts) {
            $query = '?' . \implode('&', $queryParts);
        }

        return $query;
    }

    public function formatInteger(int $integer): string
    {
        return $integer >= 0
            ? ('+' . $integer)
            : (string)$integer;
    }

    public function getLocalUrlToAction(string $action): string
    {
        return $this->getLocalUrlWithQuery([self::ACTION => $action]);
    }

    public function getLocalUrlToCancelAction(): string
    {
        return $this->getLocalUrlToAction('');
    }
}