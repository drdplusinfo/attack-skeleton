<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use Granam\Boolean\Tools\ToBoolean;
use Granam\Integer\PositiveIntegerObject;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class AttackForCalculator extends StrictObject
{
    use UsingArmaments;

    /** @var CurrentAttackValues */
    protected $currentAttackValues;
    /** @var CurrentProperties */
    protected $currentProperties;
    /** @var PreviousProperties */
    protected $previousProperties;
    /** @var PreviousArmaments */
    protected $previousArmaments;
    /** @var Tables */
    protected $tables;

    /**
     * @param CurrentAttackValues $currentAttackValues
     * @param History $history
     * @param CustomArmamentsService $customArmamentsService
     * @param Tables $tables
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function __construct(
        CurrentAttackValues $currentAttackValues,
        History $history,
        CustomArmamentsService $customArmamentsService,
        Tables $tables
    )
    {
        $this->currentAttackValues = $currentAttackValues;
        $this->currentProperties = new CurrentProperties($currentAttackValues);
        $this->previousProperties = new PreviousProperties($history);
        $this->previousArmaments = new PreviousArmaments($history, $this->previousProperties, $tables);
        $this->tables = $tables;
        $this->registerCustomArmaments($currentAttackValues, $customArmamentsService);
    }

    /**
     * @param CurrentAttackValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    protected function registerCustomArmaments(CurrentAttackValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        $this->registerCustomMeleeWeapons($currentValues, $newWeaponsService);
        $this->registerCustomRangedWeapons($currentValues, $newWeaponsService);
        $this->registerCustomBodyArmors($currentValues, $newWeaponsService);
        $this->registerCustomHelms($currentValues, $newWeaponsService);
    }

    /**
     * @param CurrentAttackValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    protected function registerCustomMeleeWeapons(CurrentAttackValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomMeleeWeaponsValues() as $customMeleeWeaponsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomMeleeWeapon(
                $customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_NAME],
                WeaponCategoryCode::getIt($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_CATEGORY]),
                Strength::getIt($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_OFFENSIVENESS]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_LENGTH]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_WOUNDS]),
                PhysicalWoundTypeCode::getIt($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_WOUND_TYPE]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_COVER]),
                new Weight(
                    $customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                ToBoolean::toBoolean($customMeleeWeaponsValue[CurrentAttackValues::CUSTOM_MELEE_WEAPON_TWO_HANDED_ONLY])
            );
        }
    }

    /**
     * @param CurrentAttackValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    protected function registerCustomRangedWeapons(CurrentAttackValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomRangedWeaponsValues() as $customRangedWeaponsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomRangedWeapon(
                $customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_NAME],
                WeaponCategoryCode::getIt($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_CATEGORY]),
                Strength::getIt($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_OFFENSIVENESS]),
                (new Distance(
                    $customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_RANGE_IN_M],
                    Distance::METER,
                    Tables::getIt()->getDistanceTable()
                ))->getBonus(),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_WOUNDS]),
                PhysicalWoundTypeCode::getIt($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_WOUND_TYPE]),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_COVER]),
                new Weight(
                    $customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                ToBoolean::toBoolean($customRangedWeaponsValue[CurrentAttackValues::CUSTOM_RANGED_WEAPON_TWO_HANDED_ONLY])
            );
        }
    }

    /**
     * @param CurrentAttackValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    protected function registerCustomBodyArmors(CurrentAttackValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomBodyArmorsValues() as $customBodyArmorsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomBodyArmor(
                $customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_NAME],
                Strength::getIt($customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_RESTRICTION]),
                ToInteger::toInteger($customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_PROTECTION]),
                new Weight(
                    $customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                new PositiveIntegerObject($customBodyArmorsValue[CurrentAttackValues::CUSTOM_BODY_ARMOR_ROUNDS_TO_PUT_ON])
            );
        }
    }

    /**
     * @param CurrentAttackValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    protected function registerCustomHelms(CurrentAttackValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomHelmsValues() as $customHelmsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomHelm(
                $customHelmsValue[CurrentAttackValues::CUSTOM_HELM_NAME],
                Strength::getIt($customHelmsValue[CurrentAttackValues::CUSTOM_HELM_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customHelmsValue[CurrentAttackValues::CUSTOM_HELM_RESTRICTION]),
                ToInteger::toInteger($customHelmsValue[CurrentAttackValues::CUSTOM_HELM_PROTECTION]),
                new Weight(
                    $customHelmsValue[CurrentAttackValues::CUSTOM_HELM_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                )
            );
        }
    }

    /**
     * @return CurrentAttackValues
     */
    protected function getCurrentAttackValues(): CurrentAttackValues
    {
        return $this->currentAttackValues;
    }

    /**
     * @return CurrentProperties
     */
    protected function getCurrentProperties(): CurrentProperties
    {
        return $this->currentProperties;
    }

    /**
     * @return PreviousProperties
     */
    protected function getPreviousProperties(): PreviousProperties
    {
        return $this->previousProperties;
    }

    /**
     * @return PreviousArmaments
     */
    protected function getPreviousArmaments(): PreviousArmaments
    {
        return $this->previousArmaments;
    }

    /**
     * @return MeleeWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentMeleeWeapon(): MeleeWeaponCode
    {
        $meleeWeaponValue = $this->currentAttackValues->getCurrentValue(Controller::MELEE_WEAPON);
        if (!$meleeWeaponValue) {
            return MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        }
        $meleeWeapon = MeleeWeaponCode::getIt($meleeWeaponValue);
        $weaponHolding = $this->getCurrentMeleeWeaponHolding($meleeWeapon);
        if (!$this->canUseWeaponlike($meleeWeapon, $weaponHolding)) {
            return MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        }

        return $meleeWeapon;
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param ItemHoldingCode $itemHoldingCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    protected function canUseWeaponlike(WeaponlikeCode $weaponlikeCode, ItemHoldingCode $itemHoldingCode): bool
    {
        return $this->canUseArmament(
            $weaponlikeCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $weaponlikeCode,
                $this->getWeaponlikeHolding($weaponlikeCode, $itemHoldingCode->getValue(), $this->tables),
                $this->currentProperties->getCurrentStrength()
            )
        );
    }

    /**
     * @param MeleeWeaponCode|null $currentWeapon
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentMeleeWeaponHolding(MeleeWeaponCode $currentWeapon = null): ItemHoldingCode
    {
        $meleeWeaponHoldingValue = $this->currentAttackValues->getCurrentValue(Controller::MELEE_WEAPON_HOLDING);
        if ($meleeWeaponHoldingValue === null) {
            $meleeWeaponHoldingValue = ItemHoldingCode::MAIN_HAND;
        }

        return $this->getWeaponlikeHolding(
            $currentWeapon ?? $this->getCurrentMeleeWeapon(),
            $meleeWeaponHoldingValue,
            $this->tables
        );
    }

    /**
     * @return ShieldCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    protected function getCurrentShieldForMelee(): ShieldCode
    {
        $currentShield = $this->getCurrentShield();
        if ($currentShield->isUnarmed()) {
            return $currentShield;
        }
        if ($this->getCurrentMeleeWeaponHolding()->holdsByTwoHands()
            || !$this->canUseShield($currentShield, $this->getCurrentMeleeShieldHolding($currentShield))
        ) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return $currentShield;
    }

    protected function canUseShield(ShieldCode $shieldCode, ItemHoldingCode $itemHoldingCode): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->canUseArmament(
            $shieldCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $shieldCode,
                $itemHoldingCode,
                $this->currentProperties->getCurrentStrength()
            )
        );
    }

    /**
     * @param ShieldCode|null $curentShield
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentMeleeShieldHolding(ShieldCode $curentShield = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getCurrentMeleeWeaponHolding(),
            $this->getCurrentMeleeWeapon(),
            $curentShield ?? ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD),
            $this->tables
        );
    }

    /**
     * @return RangedWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentRangedWeapon(): RangedWeaponCode
    {
        $rangedWeaponValue = $this->currentAttackValues->getCurrentValue(Controller::RANGED_WEAPON);
        if (!$rangedWeaponValue) {
            return RangedWeaponCode::getIt(RangedWeaponCode::SAND);
        }
        $rangedWeapon = RangedWeaponCode::getIt($rangedWeaponValue);
        $weaponHolding = $this->getWeaponlikeHolding(
            $rangedWeapon,
            $this->currentAttackValues->getCurrentValue(Controller::RANGED_WEAPON_HOLDING),
            $this->tables
        );
        if (!$this->canUseWeaponlike($rangedWeapon, $weaponHolding)) {
            return RangedWeaponCode::getIt(RangedWeaponCode::SAND);
        }

        return $rangedWeapon;
    }

    /**
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentRangedWeaponHolding(): ItemHoldingCode
    {
        $rangedWeaponHoldingValue = $this->currentAttackValues->getCurrentValue(Controller::RANGED_WEAPON_HOLDING);
        if ($rangedWeaponHoldingValue === null) {
            $rangedWeaponHoldingValue = ItemHoldingCode::MAIN_HAND;
        }

        return $this->getWeaponlikeHolding($this->getCurrentRangedWeapon(), $rangedWeaponHoldingValue, $this->tables);
    }

    protected function canUseArmament(ArmamentCode $armamentCode, Strength $strengthForArmament): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return Tables::getIt()->getArmourer()
            ->canUseArmament(
                $armamentCode,
                $strengthForArmament,
                $this->currentProperties->getCurrentSize()
            );
    }

    /**
     * @return ShieldCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    public function getCurrentShieldForRanged(): ShieldCode
    {
        $currentShield = $this->getCurrentShield();
        if ($currentShield->isUnarmed()) {
            return $currentShield;
        }
        if ($this->getCurrentRangedWeaponHolding()->holdsByTwoHands()
            || !$this->canUseShield($currentShield, $this->getCurrentRangedShieldHolding($currentShield))
        ) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return $currentShield;
    }

    /**
     * @return array
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getPossibleMeleeWeapons(): array
    {
        $weaponCodes = [
            WeaponCategoryCode::AXES => MeleeWeaponCode::getAxesValues(),
            WeaponCategoryCode::KNIVES_AND_DAGGERS => MeleeWeaponCode::getKnivesAndDaggersValues(),
            WeaponCategoryCode::MACES_AND_CLUBS => MeleeWeaponCode::getMacesAndClubsValues(),
            WeaponCategoryCode::MORNINGSTARS_AND_MORGENSTERNS => MeleeWeaponCode::getMorningstarsAndMorgensternsValues(),
            WeaponCategoryCode::SABERS_AND_BOWIE_KNIVES => MeleeWeaponCode::getSabersAndBowieKnivesValues(),
            WeaponCategoryCode::STAFFS_AND_SPEARS => MeleeWeaponCode::getStaffsAndSpearsValues(),
            WeaponCategoryCode::SWORDS => MeleeWeaponCode::getSwordsValues(),
            WeaponCategoryCode::VOULGES_AND_TRIDENTS => MeleeWeaponCode::getVoulgesAndTridentsValues(),
            WeaponCategoryCode::UNARMED => MeleeWeaponCode::getUnarmedValues(),
        ];
        foreach ($weaponCodes as &$weaponCodesOfSameCategory) {
            $weaponCodesOfSameCategory = $this->addUsabilityToMeleeWeapons($weaponCodesOfSameCategory);
        }

        return $weaponCodes;
    }

    /**
     * @param array|string[] $meleeWeaponCodeValues
     * @return array
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    protected function addUsabilityToMeleeWeapons(array $meleeWeaponCodeValues): array
    {
        $meleeWeaponCodes = [];
        foreach ($meleeWeaponCodeValues as $meleeWeaponCodeValue) {
            $meleeWeaponCodes[] = MeleeWeaponCode::getIt($meleeWeaponCodeValue);
        }

        return $this->addWeaponlikeUsability($meleeWeaponCodes, $this->getCurrentMeleeWeaponHolding());
    }

    /**
     * @param array|WeaponlikeCode[] $weaponLikeCode
     * @param ItemHoldingCode $itemHoldingCode
     * @return array
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    protected function addWeaponlikeUsability(array $weaponLikeCode, ItemHoldingCode $itemHoldingCode): array
    {
        $withUsagePossibility = [];
        foreach ($weaponLikeCode as $code) {
            $withUsagePossibility[] = [
                'code' => $code,
                'canUseIt' => $this->canUseWeaponlike($code, $itemHoldingCode),
            ];
        }

        return $withUsagePossibility;
    }

    /**
     * @return array|RangedWeaponCode[][][]
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getPossibleRangedWeapons(): array
    {
        $weaponCodes = [
            WeaponCategoryCode::THROWING_WEAPONS => RangedWeaponCode::getThrowingWeaponsValues(),
            WeaponCategoryCode::BOWS => RangedWeaponCode::getBowsValues(),
            WeaponCategoryCode::CROSSBOWS => RangedWeaponCode::getCrossbowsValues(),
        ];
        foreach ($weaponCodes as &$weaponCodesOfSameCategory) {
            $weaponCodesOfSameCategory = $this->addUsabilityToRangedWeapons($weaponCodesOfSameCategory);
        }

        return $weaponCodes;
    }

    /**
     * @param array|string[] $rangedWeaponCodeValues
     * @return array
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    protected function addUsabilityToRangedWeapons(array $rangedWeaponCodeValues): array
    {
        $meleeWeaponCodes = [];
        foreach ($rangedWeaponCodeValues as $rangedWeaponCodeValue) {
            $meleeWeaponCodes[] = RangedWeaponCode::getIt($rangedWeaponCodeValue);
        }

        return $this->addWeaponlikeUsability($meleeWeaponCodes, $this->getCurrentRangedWeaponHolding());
    }

    /**
     * @return array|BodyArmorCode[][][]
     */
    public function getPossibleBodyArmors(): array
    {
        $bodyArmors = \array_map(function (string $armorValue) {
            return BodyArmorCode::getIt($armorValue);
        }, BodyArmorCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($bodyArmors);
    }

    /**
     * @return array|HelmCode[][][]
     */
    public function getPossibleHelms(): array
    {
        $helmCodes = \array_map(function (string $helmValue) {
            return HelmCode::getIt($helmValue);
        }, HelmCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($helmCodes);
    }

    /**
     * @return array|ShieldCode[][][]
     */
    public function getPossibleShields(): array
    {
        $shieldCodes = \array_map(function (string $shieldValue) {
            return ShieldCode::getIt($shieldValue);
        }, ShieldCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($shieldCodes);
    }

    /**
     * @param array|ArmamentCode[] $armamentCodes
     * @return array
     */
    protected function addNonWeaponArmamentUsability(array $armamentCodes): array
    {
        $withUsagePossibility = [];
        foreach ($armamentCodes as $armamentCode) {
            $withUsagePossibility[] = [
                'code' => $armamentCode,
                'canUseIt' => $this->canUseArmament($armamentCode, $this->currentProperties->getCurrentStrength()),
            ];
        }

        return $withUsagePossibility;
    }

    public function getCurrentBodyArmor(): BodyArmorCode
    {
        $currentBodyArmorValue = $this->currentAttackValues->getCurrentValue(Controller::BODY_ARMOR);
        if (!$currentBodyArmorValue) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }
        $currentBodyArmor = BodyArmorCode::getIt($currentBodyArmorValue);
        if (!$this->canUseArmament($currentBodyArmor, $this->currentProperties->getCurrentStrength())) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }

        return BodyArmorCode::getIt($currentBodyArmorValue);
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOfCurrentBodyArmor(): int
    {
        return $this->getProtectionOfBodyArmor($this->getCurrentBodyArmor());
    }

    /**
     * @param BodyArmorCode $bodyArmorCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOfBodyArmor(BodyArmorCode $bodyArmorCode): int
    {
        return Tables::getIt()->getBodyArmorsTable()->getProtectionOf($bodyArmorCode);
    }

    public function getCurrentHelm(): HelmCode
    {
        $currentHelmValue = $this->currentAttackValues->getCurrentValue(Controller::HELM);
        if (!$currentHelmValue) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }
        $currentHelm = HelmCode::getIt($currentHelmValue);
        if (!$this->canUseArmament($currentHelm, $this->currentProperties->getCurrentStrength())) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }

        return HelmCode::getIt($currentHelmValue);
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getCurrentHelmProtection(): int
    {
        return $this->getProtectionOfHelm($this->getCurrentHelm());
    }

    /**
     * @param ShieldCode $shieldCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownShield
     */
    public function getCoverOfShield(ShieldCode $shieldCode): int
    {
        return Tables::getIt()->getShieldsTable()->getCoverOf($shieldCode);
    }

    /**
     * @param HelmCode $helmCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOfHelm(HelmCode $helmCode): int
    {
        return Tables::getIt()->getHelmsTable()->getProtectionOf($helmCode);
    }

    /**
     * WITHOUT usability check
     *
     * @return ShieldCode
     */
    public function getCurrentShield(): ShieldCode
    {
        $currentShieldValue = $this->currentAttackValues->getCurrentValue(Controller::SHIELD);
        if (!$currentShieldValue) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return ShieldCode::getIt($currentShieldValue);
    }

    /**
     * @param ShieldCode|null $shield = null
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentRangedShieldHolding(ShieldCode $shield = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getCurrentRangedWeaponHolding(),
            $this->getCurrentRangedWeapon(),
            $shield ?? ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD),
            $this->tables
        );
    }

    /**
     * @param ShieldCode|null $currentShield
     * @return ItemHoldingCode
     */
    public function getCurrentShieldHolding(ShieldCode $currentShield = null): ItemHoldingCode
    {
        $shieldHolding = $this->currentAttackValues->getCurrentValue(Controller::SHIELD_HOLDING);
        if ($shieldHolding === null) {
            $shieldHolding = ItemHoldingCode::OFFHAND;
        }

        return $this->getWeaponlikeHolding(
            $currentShield ?? $this->getCurrentShield(),
            $shieldHolding,
            $this->tables
        );
    }
}