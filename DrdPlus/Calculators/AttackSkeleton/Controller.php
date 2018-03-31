<?php
namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Configurator\Skeleton\History;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;

class Controller extends \DrdPlus\Configurator\Skeleton\Controller
{
    public const MELEE_WEAPON = 'melee_weapon';
    public const RANGED_WEAPON = 'ranged_weapon';
    public const STRENGTH = PropertyCode::STRENGTH;
    public const AGILITY = PropertyCode::AGILITY;
    public const KNACK = PropertyCode::KNACK;
    public const WILL = PropertyCode::WILL;
    public const INTELLIGENCE = PropertyCode::INTELLIGENCE;
    public const CHARISMA = PropertyCode::CHARISMA;
    public const SIZE = PropertyCode::SIZE;
    public const HEIGHT_IN_CM = PropertyCode::HEIGHT_IN_CM;
    public const MELEE_WEAPON_HOLDING = 'melee_weapon_holding';
    public const RANGED_WEAPON_HOLDING = 'ranged_weapon_holding';
    public const SHIELD = 'shield';
    public const BODY_ARMOR = 'body_armor';
    public const HELM = 'helm';
    public const SCROLL_FROM_TOP = 'scroll_from_top';
    // special actions
    public const ACTION = 'action';
    public const ADD_NEW_MELEE_WEAPON = 'add_new_melee_weapon';
    public const ADD_NEW_RANGED_WEAPON = 'add_new_ranged_weapon';
    public const ADD_NEW_BODY_ARMOR = 'add_new_body_armor';
    public const ADD_NEW_HELM = 'add_new_helm';

    /** @var CurrentValues */
    private $currentValues;
    /** @var CurrentProperties */
    private $currentProperties;
    /** @var Attack */
    private $attack;
    /** @var array|string[] */
    private $messagesAbout = [];

    /**
     * @param string $cookiesPostfix
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function __construct(string $cookiesPostfix)
    {
        parent::__construct($cookiesPostfix);
        $this->currentValues = new CurrentValues($_GET, $this->getHistory());
        $this->currentProperties = new CurrentProperties($this->currentValues);
        $this->attack = new Attack(
            $this->currentValues,
            $this->currentProperties,
            $this->getHistory(),
            new PreviousProperties($this->getHistory()),
            new CustomArmamentsService()
        );
    }

    protected function createHistory(string $cookiesPostfix, int $cookiesTtl = null): History
    {
        return new History(
            $this->shouldDeleteHistory(),
            $_GET, // values to remember
            !empty($_GET[self::REMEMBER_CURRENT]), // should remember given values
            $cookiesPostfix,
            $cookiesTtl
        );
    }

    private function shouldDeleteHistory(): bool
    {
        return !empty($_POST[self::DELETE_HISTORY]);
    }

    /**
     * @return CurrentValues
     */
    public function getCurrentValues(): CurrentValues
    {
        return $this->currentValues;
    }

    /**
     * @return Attack
     */
    public function getAttack(): Attack
    {
        return $this->attack;
    }

    /**
     * @return CurrentProperties
     */
    public function getCurrentProperties(): CurrentProperties
    {
        return $this->currentProperties;
    }

    public function shouldRemember(): bool
    {
        return $this->getHistory()->shouldRememberCurrent();
    }

    public function getScrollFromTop(): int
    {
        return (int)$this->currentValues->getValue(self::SCROLL_FROM_TOP);
    }

    /**
     * @return array|MeleeWeaponCode[][][]
     */
    public function getMeleeWeapons(): array
    {
        $weaponCodes = $this->attack->getPossibleMeleeWeapons();
        $countOfUnusable = 0;
        foreach ($weaponCodes as $weaponCodesOfSameCategory) {
            $countOfUnusable += $this->countUnusable($weaponCodesOfSameCategory);
        }
        if ($countOfUnusable > 0) {
            $weaponWord = 'zbraň';
            if ($countOfUnusable >= 5) {
                $weaponWord = 'zbraní';
            } elseif ($countOfUnusable >= 2) {
                $weaponWord = 'zbraně';
            }
            $this->messagesAbout['melee']['unusable'] = "Kvůli chybějící síle nemůžeš použít $countOfUnusable $weaponWord na blízko.";
        }

        return $weaponCodes;
    }

    /**
     * @param array|bool[][] $items
     * @return int
     */
    private function countUnusable(array $items): int
    {
        $count = 0;
        foreach ($items as $item) {
            if (!$item['canUseIt']) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return array|RangedWeaponCode[][][]
     */
    public function getRangedWeapons(): array
    {
        $weaponCodes = $this->attack->getPossibleRangedWeapons();
        $countOfUnusable = 0;
        foreach ($weaponCodes as $weaponCodesOfSameCategory) {
            $countOfUnusable += $this->countUnusable($weaponCodesOfSameCategory);
        }
        if ($countOfUnusable > 0) {
            $weaponWord = 'zbraň';
            if ($countOfUnusable >= 5) {
                $weaponWord = 'zbraní';
            } elseif ($countOfUnusable >= 2) {
                $weaponWord = 'zbraně';
            }
            $this->messagesAbout['ranged']['unusable'] = "Kvůli chybějící síle nemůžeš použít $countOfUnusable $weaponWord na dálku.";
        }

        return $weaponCodes;
    }

    /**
     * @return array|ShieldCode[][][]
     */
    public function getShields(): array
    {
        $shieldCodes = $this->attack->getPossibleShields();
        $countOfUnusable = $this->countUnusable($shieldCodes);
        if ($countOfUnusable > 0) {
            $shieldWord = 'štít';
            if ($countOfUnusable >= 5) {
                $shieldWord = 'štítů';
            } elseif ($countOfUnusable >= 2) {
                $shieldWord = 'štíty';
            }
            $this->messagesAbout['shields']['unusable'] = "Kvůli chybějící síle nemůžeš použít $countOfUnusable $shieldWord.";
        }

        return $shieldCodes;
    }

    /**
     * @return array
     */
    public function getBodyArmors(): array
    {
        $bodyArmors = $this->attack->getPossibleBodyArmors();
        $countOfUnusable = $this->countUnusable($bodyArmors);
        if ($countOfUnusable > 0) {
            $armorWord = 'zbroj';
            if ($countOfUnusable >= 5) {
                $armorWord = 'zbrojí';
            } elseif ($countOfUnusable >= 2) {
                $armorWord = 'zbroje';
            }
            $this->messagesAbout['armors']['unusable'] = "Kvůli chybějící síle nemůžeš použít $countOfUnusable $armorWord.";
        }

        return $bodyArmors;
    }

    /**
     * @return array|HelmCode[][]
     */
    public function getHelms(): array
    {
        $helmCodes = $this->attack->getPossibleHelms();
        $countOfUnusable = $this->countUnusable($helmCodes);
        $this->addUnusableMessage($countOfUnusable, 'helms', 'helmu', 'helmy', 'helem');

        return $helmCodes;
    }

    private function addUnusableMessage(int $countOfUnusable, string $key, string $single, string $few, string $many): void
    {
        if ($countOfUnusable > 0) {
            $word = $single;
            if ($countOfUnusable >= 5) {
                $word = $many;
            } elseif ($countOfUnusable >= 2) {
                $word = $few;
            }

            $this->messagesAbout[$key]['unusable'] = "Kvůli chybějící síle nemůžeš použít $countOfUnusable $word.";
        }
    }

    /**
     * @param int|IntegerInterface $previous
     * @param int|IntegerInterface $current
     * @return string
     */
    public function getCssClassForChangedValue($previous, $current): string
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (ToInteger::toInteger($previous) < ToInteger::toInteger($current)) {
            return 'increased';
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (ToInteger::toInteger($previous) > ToInteger::toInteger($current)) {
            return 'decreased';
        }

        return '';
    }

    public function getMessagesAboutMelee(): array
    {
        return $this->messagesAbout['melee'] ?? [];
    }

    public function getMessagesAboutRanged(): array
    {
        return $this->messagesAbout['ranged'] ?? [];
    }

    public function getMessagesAboutShields(): array
    {
        return $this->messagesAbout['shields'] ?? [];
    }

    public function getMessagesAboutHelms(): array
    {
        return $this->messagesAbout['helms'] ?? [];
    }

    public function getMessagesAboutArmors(): array
    {
        return $this->messagesAbout['armors'] ?? [];
    }

    public function getCurrentUrlWithQuery(array $additionalParameters = []): string
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
                    $queryParts[] = \urlencode("{$name}[{$index}]") . '=' . \urlencode($item);
                }
            } else {
                $queryParts[] = \urlencode($name) . '=' . \urlencode($value);
            }
        }
        $query = '';
        if ($queryParts) {
            $query = '?' . \implode('&', $queryParts);
        }

        return $query;
    }

    public function addingNewMeleeWeapon(): bool
    {
        return $this->currentValues->getCurrentValue(self::ACTION) === self::ADD_NEW_MELEE_WEAPON;
    }

    public function addingNewRangedWeapon(): bool
    {
        return $this->currentValues->getCurrentValue(self::ACTION) === self::ADD_NEW_RANGED_WEAPON;
    }

    public function addingNewBodyArmor(): bool
    {
        return $this->currentValues->getCurrentValue(self::ACTION) === self::ADD_NEW_BODY_ARMOR;
    }

    public function addingNewHelm(): bool
    {
        return $this->currentValues->getCurrentValue(self::ACTION) === self::ADD_NEW_HELM;
    }
}