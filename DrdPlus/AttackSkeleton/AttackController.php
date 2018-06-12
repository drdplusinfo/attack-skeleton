<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;

/**
 * @method CurrentAttackValues getCurrentValues
 */
class AttackController extends \DrdPlus\CalculatorSkeleton\CalculatorController
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

    /** @var CurrentProperties */
    private $currentProperties;
    /** @var AttackForCalculator */
    private $attack;
    /** @var array|string[] */
    private $messagesAbout = [];

    /**
     * @param string $documentRoot
     * @param string $vendorRoot
     * @param string $sourceCodeUrl
     * @param string $cookiesPostfix
     * @param string|null $partsRoot
     * @param string|null $genericPartsRoot
     * @param int|null $cookiesTtl = null
     * @param array|null $selectedValues = null
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function __construct(
        string $sourceCodeUrl,
        string $cookiesPostfix,
        string $documentRoot,
        string $vendorRoot,
        string $partsRoot = null,
        string $genericPartsRoot = null,
        int $cookiesTtl = null,
        array $selectedValues = null
    )
    {
        $partsRoot = $partsRoot ?? \file_exists($documentRoot . '/parts')
                ? ($documentRoot . '/parts')
                : ($vendorRoot . '/drd-plus/attack-skeleton/parts');
        $genericPartsRoot = $genericPartsRoot ?? (__DIR__ . '/../../parts/attack-skeleton');
        parent::__construct(
            $sourceCodeUrl,
            $cookiesPostfix,
            $documentRoot,
            $vendorRoot,
            $partsRoot,
            $genericPartsRoot,
            $cookiesTtl,
            $selectedValues
        );
        $this->currentProperties = new CurrentProperties($this->getCurrentValues());
        $this->attack = new AttackForCalculator(
            $this->getCurrentValues(),
            $this->getHistory(),
            new CustomArmamentsService(),
            Tables::getIt()
        );
    }

    /**
     * @param array $selectedValues
     * @param Memory $memory
     * @return \DrdPlus\CalculatorSkeleton\CurrentValues|CurrentAttackValues
     */
    protected function createCurrentValues(array $selectedValues, Memory $memory): CurrentValues
    {
        return new CurrentAttackValues($selectedValues, $memory);
    }

    /**
     * @return AttackForCalculator
     */
    public function getAttack(): AttackForCalculator
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

    public function getScrollFromTop(): int
    {
        return (int)$this->getCurrentValues()->getCurrentValue(self::SCROLL_FROM_TOP);
    }

    /**
     * @return array|MeleeWeaponCode[][][]
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
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
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
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
        $this->addUnusableMessage($this->countUnusable($helmCodes), 'helms', 'helmu', 'helmy', 'helem');

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

    public function getMessagesAboutMeleeWeapons(): array
    {
        $this->getMeleeWeapons();

        return $this->messagesAbout['melee'] ?? [];
    }

    public function getMessagesAboutRangedWeapons(): array
    {
        $this->getRangedWeapons();

        return $this->messagesAbout['ranged'] ?? [];
    }

    public function getMessagesAboutShields(): array
    {
        $this->getShields();

        return $this->messagesAbout['shields'] ?? [];
    }

    public function getMessagesAboutHelms(): array
    {
        $this->getHelms();

        return $this->messagesAbout['helms'] ?? [];
    }

    public function getMessagesAboutArmors(): array
    {
        $this->getBodyArmors();

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

    public function isAddingNewMeleeWeapon(): bool
    {
        return $this->getCurrentValues()->getSelectedValue(self::ACTION) === self::ADD_NEW_MELEE_WEAPON;
    }

    public function isAddingNewRangedWeapon(): bool
    {
        return $this->getCurrentValues()->getSelectedValue(self::ACTION) === self::ADD_NEW_RANGED_WEAPON;
    }

    public function isAddingNewBodyArmor(): bool
    {
        return $this->getCurrentValues()->getSelectedValue(self::ACTION) === self::ADD_NEW_BODY_ARMOR;
    }

    public function isAddingNewHelm(): bool
    {
        return $this->getCurrentValues()->getSelectedValue(self::ACTION) === self::ADD_NEW_HELM;
    }

    public function isAddingNewShield(): bool
    {
        return $this->getCurrentValues()->getSelectedValue(self::ACTION) === self::ADD_NEW_SHIELD;
    }

    public function getBodyPropertiesContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/body_properties.php'));
    }

    private function getGenericPartContent(string $partScriptName): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $controller = $this;
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include $this->getGenericPartsRoot() . '/' . $partScriptName;

        return \ob_get_clean();
    }

    public function getArmorContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/armor.php'));
    }

    public function getHelmContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/helm.php'));
    }

    public function getMeleeWeaponContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/melee_weapon.php'));
    }

    public function getRangedWeaponContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/ranged_weapon.php'));
    }

    public function getShieldContent(): string
    {
        return $this->getGenericPartContent(\basename(__DIR__ . '/../../parts/attack-skeleton/shield.php'));
    }

    public function getAddCustomBodyArmorContent(): string
    {
        return $this->getGenericPartContent(
            'add-custom/' . \basename(__DIR__ . '/../../parts/attack-skeleton/add-custom/add_custom_body_armor.php')
        );
    }

    public function getAddCustomHelmContent(): string
    {
        return $this->getGenericPartContent(
            'add-custom/' . \basename(__DIR__ . '/../../parts/attack-skeleton/add-custom/add_custom_helm.php')
        );
    }

    public function getAddCustomMeleeWeaponContent(): string
    {
        return $this->getGenericPartContent(
            'add-custom/' . \basename(__DIR__ . '/../../parts/attack-skeleton/add-custom/add_custom_melee_weapon.php')
        );
    }

    public function getAddCustomRangedWeaponContent(): string
    {
        return $this->getGenericPartContent(
            'add-custom/' . \basename(__DIR__ . '/../../parts/attack-skeleton/add-custom/add_custom_ranged_weapon.php')
        );
    }

    public function getAddCustomShield(): string
    {
        return $this->getGenericPartContent(
            'add-custom/' . \basename(__DIR__ . '/../../parts/attack-skeleton/add-custom/add_custom_shield.php')
        );
    }
}