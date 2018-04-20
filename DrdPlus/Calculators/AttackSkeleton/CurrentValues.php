<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Configurator\Skeleton\History;
use DrdPlus\Configurator\Skeleton\Memory;
use Granam\Strict\Object\StrictObject;

class CurrentValues extends StrictObject
{
    // melee weapon
    public const CUSTOM_MELEE_WEAPON_NAME = 'custom_melee_weapon_name';
    public const CUSTOM_MELEE_WEAPON_CATEGORY = 'custom_melee_weapon_category';
    public const CUSTOM_MELEE_WEAPON_REQUIRED_STRENGTH = 'custom_melee_weapon_required_strength';
    public const CUSTOM_MELEE_WEAPON_LENGTH = 'custom_melee_weapon_length';
    public const CUSTOM_MELEE_WEAPON_OFFENSIVENESS = 'custom_melee_weapon_offensiveness';
    public const CUSTOM_MELEE_WEAPON_WOUNDS = 'custom_melee_weapon_wounds';
    public const CUSTOM_MELEE_WEAPON_WOUND_TYPE = 'custom_melee_weapon_wound_type';
    public const CUSTOM_MELEE_WEAPON_COVER = 'custom_melee_weapon_cover';
    public const CUSTOM_MELEE_WEAPON_WEIGHT = 'custom_melee_weapon_weight';
    public const CUSTOM_MELEE_WEAPON_TWO_HANDED_ONLY = 'custom_melee_weapon_two_handed_only';
    // ranged weapon
    public const CUSTOM_RANGED_WEAPON_NAME = 'custom_ranged_weapon_name';
    public const CUSTOM_RANGED_WEAPON_CATEGORY = 'custom_ranged_weapon_category';
    public const CUSTOM_RANGED_WEAPON_REQUIRED_STRENGTH = 'custom_ranged_weapon_required_strength';
    public const CUSTOM_RANGED_WEAPON_RANGE_IN_M = 'custom_ranged_weapon_range_in_m';
    public const CUSTOM_RANGED_WEAPON_OFFENSIVENESS = 'custom_ranged_weapon_offensiveness';
    public const CUSTOM_RANGED_WEAPON_WOUNDS = 'custom_ranged_weapon_wounds';
    public const CUSTOM_RANGED_WEAPON_WOUND_TYPE = 'custom_ranged_weapon_wound_type';
    public const CUSTOM_RANGED_WEAPON_COVER = 'custom_ranged_weapon_cover';
    public const CUSTOM_RANGED_WEAPON_WEIGHT = 'custom_ranged_weapon_weight';
    public const CUSTOM_RANGED_WEAPON_TWO_HANDED_ONLY = 'custom_ranged_weapon_two_handed_only';
    // body armor
    public const CUSTOM_BODY_ARMOR_NAME = 'custom_body_armor_name';
    public const CUSTOM_BODY_ARMOR_REQUIRED_STRENGTH = 'custom_body_armor_required_strength';
    public const CUSTOM_BODY_ARMOR_RESTRICTION = 'custom_body_armor_restriction';
    public const CUSTOM_BODY_ARMOR_PROTECTION = 'custom_body_armor_protection';
    public const CUSTOM_BODY_ARMOR_WEIGHT = 'custom_body_armor_weight';
    public const CUSTOM_BODY_ARMOR_ROUNDS_TO_PUT_ON = 'custom_body_armor_rounds_to_put_on';
    // helm
    public const CUSTOM_HELM_NAME = 'custom_helm_name';
    public const CUSTOM_HELM_REQUIRED_STRENGTH = 'custom_helm_required_strength';
    public const CUSTOM_HELM_RESTRICTION = 'custom_helm_restriction';
    public const CUSTOM_HELM_PROTECTION = 'custom_helm_protection';
    public const CUSTOM_HELM_WEIGHT = 'custom_helm_weight';

    /** @var array */
    private $valuesFromInput;
    /** @var History */
    private $memory;
    /** @var array */
    private $customRangedWeaponsValues;
    /** @var array */
    private $customMeleeWeaponsValues;
    /** @var array */
    private $customBodyArmorsValues;
    /** @var array */
    private $customHelmsValues;

    /**
     * @param array $valuesFromInput
     * @param Memory $memory
     */
    public function __construct(array $valuesFromInput, Memory $memory)
    {
        $this->valuesFromInput = $valuesFromInput;
        $this->memory = $memory;
    }

    /**
     * @param string $name
     * @return string|string[]|null
     */
    public function getValue(string $name)
    {
        if (\array_key_exists($name, $this->valuesFromInput)) {
            return $this->valuesFromInput[$name];
        }

        return $this->memory->getValue($name);
    }

    /**
     * @param string $name
     * @return null|string[]|array|string
     */
    public function getCurrentValue(string $name)
    {
        return $this->valuesFromInput[$name] ?? null;
    }

    /**
     * @return array|string[][]
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function getCustomMeleeWeaponsValues(): array
    {
        if ($this->customMeleeWeaponsValues !== null) {
            return $this->customMeleeWeaponsValues;
        }
        $this->customMeleeWeaponsValues = $this->assembleCustomMeleeWeaponsValues();

        return $this->customMeleeWeaponsValues;
    }

    /**
     * @return array
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function assembleCustomMeleeWeaponsValues(): array
    {
        return $this->assembleCustomArmamentsValues(
            [
                self::CUSTOM_MELEE_WEAPON_NAME,
                self::CUSTOM_MELEE_WEAPON_CATEGORY,
                self::CUSTOM_MELEE_WEAPON_OFFENSIVENESS,
                self::CUSTOM_MELEE_WEAPON_LENGTH,
                self::CUSTOM_MELEE_WEAPON_REQUIRED_STRENGTH,
                self::CUSTOM_MELEE_WEAPON_WOUND_TYPE,
                self::CUSTOM_MELEE_WEAPON_WOUNDS,
                self::CUSTOM_MELEE_WEAPON_COVER,
                self::CUSTOM_MELEE_WEAPON_WEIGHT,
                self::CUSTOM_MELEE_WEAPON_TWO_HANDED_ONLY,
            ],
            self::CUSTOM_MELEE_WEAPON_NAME,
            self::CUSTOM_MELEE_WEAPON_TWO_HANDED_ONLY
        );
    }

    /**
     * @param array $customArmamentKeys
     * @param string $customArmamentNameKey
     * @param string $customArmamentTwoHandedOnlyKey
     * @return array
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function assembleCustomArmamentsValues(
        array $customArmamentKeys,
        string $customArmamentNameKey,
        string $customArmamentTwoHandedOnlyKey
    ): array
    {
        $nameIndexedValues = [];
        $armamentNames = (array)$this->getValue($customArmamentNameKey);
        foreach ($armamentNames as $index => $armamentName) {
            $customArmament = [];
            foreach ($customArmamentKeys as $typeName) {
                $sameTypeValues = $typeName === $customArmamentNameKey
                    ? $armamentNames
                    : (array)$this->getValue($typeName);
                if ($typeName === $customArmamentTwoHandedOnlyKey) {
                    $sameTypeValues[$index] = (bool)($sameTypeValues[$index] ?? false);
                } else {
                    if (($sameTypeValues[$index] ?? null) === null) {
                        throw new Exceptions\BrokenNewArmamentValues(
                            "Missing '{$typeName}' on index '{$index}' for a new armament '{$armamentName}'"
                        );
                    }
                }
                $customArmament[$typeName] = $sameTypeValues[$index];
            }
            // re-index everything from integer index to armament name
            $nameIndexedValues[$armamentName] = $customArmament;
        }

        return $nameIndexedValues;
    }

    /**
     * @return array|string[][]
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function getCustomRangedWeaponsValues(): array
    {
        if ($this->customRangedWeaponsValues !== null) {
            return $this->customRangedWeaponsValues;
        }
        $this->customRangedWeaponsValues = $this->assembleCustomRangedWeaponsValues();

        return $this->customRangedWeaponsValues;
    }

    /**
     * @return array
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function assembleCustomRangedWeaponsValues(): array
    {
        return $this->assembleCustomArmamentsValues(
            [
                self::CUSTOM_RANGED_WEAPON_NAME,
                self::CUSTOM_RANGED_WEAPON_CATEGORY,
                self::CUSTOM_RANGED_WEAPON_OFFENSIVENESS,
                self::CUSTOM_RANGED_WEAPON_RANGE_IN_M,
                self::CUSTOM_RANGED_WEAPON_REQUIRED_STRENGTH,
                self::CUSTOM_RANGED_WEAPON_WOUND_TYPE,
                self::CUSTOM_RANGED_WEAPON_WOUNDS,
                self::CUSTOM_RANGED_WEAPON_COVER,
                self::CUSTOM_RANGED_WEAPON_WEIGHT,
                self::CUSTOM_RANGED_WEAPON_TWO_HANDED_ONLY,
            ],
            self::CUSTOM_RANGED_WEAPON_NAME,
            self::CUSTOM_RANGED_WEAPON_TWO_HANDED_ONLY
        );
    }

    /**
     * @return array|string[][]
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function getCustomBodyArmorsValues(): array
    {
        if ($this->customBodyArmorsValues !== null) {
            return $this->customBodyArmorsValues;
        }
        $this->customBodyArmorsValues = $this->assembleCustomBodyArmorsValues();

        return $this->customBodyArmorsValues;
    }

    /**
     * @return array
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function assembleCustomBodyArmorsValues(): array
    {
        return $this->assembleCustomArmamentsValues(
            [
                self::CUSTOM_BODY_ARMOR_NAME,
                self::CUSTOM_BODY_ARMOR_REQUIRED_STRENGTH,
                self::CUSTOM_BODY_ARMOR_RESTRICTION,
                self::CUSTOM_BODY_ARMOR_PROTECTION,
                self::CUSTOM_BODY_ARMOR_WEIGHT,
                self::CUSTOM_BODY_ARMOR_ROUNDS_TO_PUT_ON,
            ],
            self::CUSTOM_BODY_ARMOR_NAME,
            self::CUSTOM_BODY_ARMOR_NAME
        );
    }

    /**
     * @return array|string[][]
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function getCustomHelmsValues(): array
    {
        if ($this->customHelmsValues !== null) {
            return $this->customHelmsValues;
        }
        $this->customHelmsValues = $this->assembleCustomHelmsValues();

        return $this->customHelmsValues;
    }

    /**
     * @return array
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function assembleCustomHelmsValues(): array
    {
        return $this->assembleCustomArmamentsValues(
            [
                self::CUSTOM_HELM_NAME,
                self::CUSTOM_HELM_REQUIRED_STRENGTH,
                self::CUSTOM_HELM_RESTRICTION,
                self::CUSTOM_HELM_PROTECTION,
                self::CUSTOM_HELM_WEIGHT,
            ],
            self::CUSTOM_HELM_NAME,
            self::CUSTOM_HELM_NAME
        );
    }
}