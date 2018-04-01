<?php
namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\Body\WoundTypeCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Configurator\Skeleton\History;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use Granam\Boolean\Tools\ToBoolean;
use Granam\Integer\PositiveIntegerObject;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

class Attack extends StrictObject
{
    /** @var CurrentValues */
    private $currentValues;
    /** @var CurrentProperties */
    private $currentProperties;
    /** @var History */
    private $history;
    /** @var PreviousProperties */
    private $previousProperties;

    /**
     * @param CurrentValues $currentValues
     * @param CurrentProperties $currentProperties
     * @param History $history
     * @param PreviousProperties $previousProperties
     * @param CustomArmamentsService $newWeaponService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    public function __construct(
        CurrentValues $currentValues,
        CurrentProperties $currentProperties,
        History $history,
        PreviousProperties $previousProperties,
        CustomArmamentsService $newWeaponService
    )
    {
        $this->currentValues = $currentValues;
        $this->currentProperties = $currentProperties;
        $this->history = $history;
        $this->previousProperties = $previousProperties;
        $this->registerCustomArmaments($currentValues, $newWeaponService);
    }

    /**
     * @param CurrentValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function registerCustomArmaments(CurrentValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        $this->registerCustomMeleeWeapons($currentValues, $newWeaponsService);
        $this->registerCustomRangedWeapons($currentValues, $newWeaponsService);
        $this->registerCustomBodyArmors($currentValues, $newWeaponsService);
        $this->registerCustomHelms($currentValues, $newWeaponsService);
    }

    /**
     * @param CurrentValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function registerCustomMeleeWeapons(CurrentValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomMeleeWeaponsValues() as $customMeleeWeaponsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomMeleeWeapon(
                $customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_NAME],
                WeaponCategoryCode::getIt($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_CATEGORY]),
                Strength::getIt($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_OFFENSIVENESS]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_LENGTH]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_WOUNDS]),
                WoundTypeCode::getIt($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_WOUND_TYPE]),
                ToInteger::toInteger($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_COVER]),
                new Weight(
                    $customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                ToBoolean::toBoolean($customMeleeWeaponsValue[CurrentValues::CUSTOM_MELEE_WEAPON_TWO_HANDED_ONLY])
            );
        }
    }

    /**
     * @param CurrentValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function registerCustomRangedWeapons(CurrentValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomRangedWeaponsValues() as $customRangedWeaponsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomRangedWeapon(
                $customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_NAME],
                WeaponCategoryCode::getIt($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_CATEGORY]),
                Strength::getIt($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_OFFENSIVENESS]),
                (new Distance(
                    $customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_RANGE_IN_M],
                    Distance::METER,
                    Tables::getIt()->getDistanceTable()
                ))->getBonus(),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_WOUNDS]),
                WoundTypeCode::getIt($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_WOUND_TYPE]),
                ToInteger::toInteger($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_COVER]),
                new Weight(
                    $customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                ToBoolean::toBoolean($customRangedWeaponsValue[CurrentValues::CUSTOM_RANGED_WEAPON_TWO_HANDED_ONLY])
            );
        }
    }

    /**
     * @param CurrentValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function registerCustomBodyArmors(CurrentValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomBodyArmorsValues() as $customBodyArmorsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomBodyArmor(
                $customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_NAME],
                Strength::getIt($customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_RESTRICTION]),
                ToInteger::toInteger($customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_PROTECTION]),
                new Weight(
                    $customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                ),
                new PositiveIntegerObject($customBodyArmorsValue[CurrentValues::CUSTOM_BODY_ARMOR_ROUNDS_TO_PUT_ON])
            );
        }
    }

    /**
     * @param CurrentValues $currentValues
     * @param CustomArmamentsService $newWeaponsService
     * @throws \DrdPlus\Calculators\AttackSkeleton\Exceptions\BrokenNewArmamentValues
     */
    private function registerCustomHelms(CurrentValues $currentValues, CustomArmamentsService $newWeaponsService): void
    {
        foreach ($currentValues->getCustomHelmsValues() as $customHelmsValue) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $newWeaponsService->addCustomHelm(
                $customHelmsValue[CurrentValues::CUSTOM_HELM_NAME],
                Strength::getIt($customHelmsValue[CurrentValues::CUSTOM_HELM_REQUIRED_STRENGTH]),
                ToInteger::toInteger($customHelmsValue[CurrentValues::CUSTOM_HELM_RESTRICTION]),
                ToInteger::toInteger($customHelmsValue[CurrentValues::CUSTOM_HELM_PROTECTION]),
                new Weight(
                    $customHelmsValue[CurrentValues::CUSTOM_HELM_WEIGHT],
                    Weight::KG,
                    Tables::getIt()->getWeightTable()
                )
            );
        }
    }

    /**
     * @return MeleeWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    private function getPreviousMeleeWeapon(): MeleeWeaponCode
    {
        $meleeWeaponValue = $this->history->getValue(Controller::MELEE_WEAPON);
        if (!$meleeWeaponValue) {
            return MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        }
        $meleeWeapon = MeleeWeaponCode::getIt($meleeWeaponValue);
        $weaponHolding = $this->getWeaponHolding(
            $meleeWeapon,
            $this->history->getValue(Controller::MELEE_WEAPON_HOLDING)
        );
        if (!$this->couldUseWeaponlike($meleeWeapon, $weaponHolding)) {
            return MeleeWeaponCode::getIt(MeleeWeaponCode::HAND);
        }

        return $meleeWeapon;
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param string $weaponHolding
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    private function getWeaponHolding(WeaponlikeCode $weaponlikeCode, string $weaponHolding): ItemHoldingCode
    {
        if ($this->isTwoHandedOnly($weaponlikeCode)) {
            return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
        }
        if ($this->isOneHandedOnly($weaponlikeCode)) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }
        if (!$weaponHolding) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return ItemHoldingCode::getIt($weaponHolding);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    private function isTwoHandedOnly(WeaponlikeCode $weaponlikeCode): bool
    {
        return Tables::getIt()->getArmourer()->isTwoHandedOnly($weaponlikeCode);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    private function isOneHandedOnly(WeaponlikeCode $weaponlikeCode): bool
    {
        return Tables::getIt()->getArmourer()->isOneHandedOnly($weaponlikeCode);
    }

    /**
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    private function getPreviousMeleeWeaponHolding(): ItemHoldingCode
    {
        $previousMeleeWeaponHoldingValue = $this->history->getValue(Controller::MELEE_WEAPON_HOLDING);
        if ($previousMeleeWeaponHoldingValue === null) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return $this->getWeaponHolding($this->getPreviousMeleeWeapon(), $previousMeleeWeaponHoldingValue);
    }

    /**
     * @return CurrentValues
     */
    protected function getCurrentValues(): CurrentValues
    {
        return $this->currentValues;
    }

    /**
     * @return CurrentProperties
     */
    protected function getCurrentProperties(): CurrentProperties
    {
        return $this->currentProperties;
    }

    /**
     * @return History
     */
    protected function getHistory(): History
    {
        return $this->history;
    }

    /**
     * @return PreviousProperties
     */
    protected function getPreviousProperties(): PreviousProperties
    {
        return $this->previousProperties;
    }

    /**
     * @return MeleeWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getCurrentMeleeWeapon(): MeleeWeaponCode
    {
        $meleeWeaponValue = $this->currentValues->getValue(Controller::MELEE_WEAPON);
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
    private function canUseWeaponlike(WeaponlikeCode $weaponlikeCode, ItemHoldingCode $itemHoldingCode): bool
    {
        return $this->canUseArmament(
            $weaponlikeCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $weaponlikeCode,
                $this->getWeaponHolding($weaponlikeCode, $itemHoldingCode->getValue()),
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
        $meleeWeaponHoldingValue = $this->currentValues->getValue(Controller::MELEE_WEAPON_HOLDING);
        if ($meleeWeaponHoldingValue === null) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return $this->getWeaponHolding($currentWeapon ?? $this->getCurrentMeleeWeapon(), $meleeWeaponHoldingValue);
    }

    /**
     * @return ShieldCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    private function getSelectedShieldForMelee(): ShieldCode
    {
        $selectedShield = $this->getSelectedShield();
        if ($selectedShield->isUnarmed()) {
            return $selectedShield;
        }
        if ($this->getCurrentMeleeWeaponHolding()->holdsByTwoHands()
            || !$this->canUseShield($selectedShield, $this->getSelectedMeleeShieldHolding($selectedShield))
        ) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return $selectedShield;
    }

    private function canUseShield(ShieldCode $shieldCode, ItemHoldingCode $itemHoldingCode): bool
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
     * @param ShieldCode|null $selectedShield
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getSelectedMeleeShieldHolding(ShieldCode $selectedShield = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getCurrentMeleeWeaponHolding(),
            $this->getCurrentMeleeWeapon(),
            $selectedShield
        );
    }

    /**
     * @return RangedWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getSelectedRangedWeapon(): RangedWeaponCode
    {
        $rangedWeaponValue = $this->currentValues->getValue(Controller::RANGED_WEAPON);
        if (!$rangedWeaponValue) {
            return RangedWeaponCode::getIt(RangedWeaponCode::SAND);
        }
        $rangedWeapon = RangedWeaponCode::getIt($rangedWeaponValue);
        $weaponHolding = $this->getWeaponHolding(
            $rangedWeapon,
            $this->currentValues->getValue(Controller::RANGED_WEAPON_HOLDING)
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
    public function getSelectedRangedWeaponHolding(): ItemHoldingCode
    {
        $rangedWeaponHoldingValue = $this->currentValues->getValue(Controller::RANGED_WEAPON_HOLDING);
        if ($rangedWeaponHoldingValue === null) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return $this->getWeaponHolding($this->getSelectedRangedWeapon(), $rangedWeaponHoldingValue);
    }

    /**
     * @return RangedWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    private function getPreviousRangedWeapon(): RangedWeaponCode
    {
        $rangedWeaponValue = $this->history->getValue(Controller::RANGED_WEAPON);
        if (!$rangedWeaponValue) {
            return RangedWeaponCode::getIt(RangedWeaponCode::SAND);
        }
        $rangedWeapon = RangedWeaponCode::getIt($rangedWeaponValue);
        $weaponHolding = $this->getWeaponHolding(
            $rangedWeapon,
            $this->history->getValue(Controller::RANGED_WEAPON_HOLDING)
        );
        if (!$this->couldUseWeaponlike($rangedWeapon, $weaponHolding)) {
            return RangedWeaponCode::getIt(RangedWeaponCode::SAND);
        }

        return $rangedWeapon;
    }

    private function canUseArmament(ArmamentCode $armamentCode, Strength $strengthForArmament): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return Tables::getIt()->getArmourer()
            ->canUseArmament(
                $armamentCode,
                $strengthForArmament,
                $this->currentProperties->getCurrentSize()
            );
    }

    private function couldUseWeaponlike(WeaponlikeCode $weaponlikeCode, ItemHoldingCode $itemHoldingCode): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->couldUseArmament(
            $weaponlikeCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $weaponlikeCode,
                $this->getWeaponHolding($weaponlikeCode, $itemHoldingCode->getValue()),
                $this->previousProperties->getPreviousStrength()
            )
        );
    }

    private function couldUseArmament(ArmamentCode $armamentCode, Strength $strengthForArmament): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return Tables::getIt()->getArmourer()
            ->canUseArmament(
                $armamentCode,
                $strengthForArmament,
                $this->previousProperties->getPreviousSize()
            );
    }

    /**
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    private function getPreviousRangedWeaponHolding(): ItemHoldingCode
    {
        $rangedWeaponHoldingValue = $this->history->getValue(Controller::RANGED_WEAPON_HOLDING);
        if ($rangedWeaponHoldingValue === null) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return $this->getWeaponHolding($this->getPreviousRangedWeapon(), $rangedWeaponHoldingValue);
    }

    /**
     * @return ShieldCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    public function getSelectedShieldForRanged(): ShieldCode
    {
        $selectedShield = $this->getSelectedShield();
        if ($selectedShield->isUnarmed()) {
            return $selectedShield;
        }
        if ($this->getSelectedRangedWeaponHolding()->holdsByTwoHands()
            || !$this->canUseShield($selectedShield, $this->getSelectedRangedShieldHolding($selectedShield))
        ) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return $selectedShield;
    }

    /**
     * @return ShieldCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    public function getPreviousShield(): ShieldCode
    {
        $previousShieldValue = $this->history->getValue(Controller::SHIELD);
        if (!$previousShieldValue) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }
        $previousShield = ShieldCode::getIt($previousShieldValue);
        if ($this->getPreviousMeleeWeaponHolding()->holdsByTwoHands()
            || $this->getPreviousRangedWeaponHolding()->holdsByTwoHands()
            || !$this->couldUseShield($previousShield, $this->getPreviousMeleeShieldHolding($previousShield))
            || !$this->couldUseShield($previousShield, $this->getPreviousRangedShieldHolding($previousShield))
        ) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return $previousShield;
    }

    private function couldUseShield(ShieldCode $shieldCode, ItemHoldingCode $itemHoldingCode): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->canUseArmament(
            $shieldCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $shieldCode,
                $itemHoldingCode,
                $this->previousProperties->getPreviousStrength()
            )
        );
    }

    /**
     * @param ShieldCode $shieldCode = null
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getPreviousRangedShieldHolding(ShieldCode $shieldCode = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getPreviousRangedWeaponHolding(),
            $this->getPreviousRangedWeapon(),
            $shieldCode
        );
    }

    /**
     * @param ItemHoldingCode $weaponHolding
     * @param WeaponlikeCode $weaponlikeCode
     * @param ShieldCode|null $shield = null
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    private function getShieldHolding(ItemHoldingCode $weaponHolding, WeaponlikeCode $weaponlikeCode, ShieldCode $shield = null): ItemHoldingCode
    {
        if ($weaponHolding->holdsByTwoHands()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            if (Tables::getIt()->getArmourer()->canHoldItByTwoHands($shield ?? $this->getSelectedShieldForMelee())) {
                // because two-handed weapon has to be dropped to use shield and then both hands can be used for shield
                return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
            }

            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if ($weaponlikeCode->isUnarmed()
            && Tables::getIt()->getArmourer()->canHoldItByTwoHands($shield ?? $this->getSelectedShieldForMelee())
        ) {
            return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
        }

        return $weaponHolding->getOpposite();
    }

    /**
     * @param ShieldCode|null $shieldCode = null
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function getPreviousMeleeShieldHolding(ShieldCode $shieldCode = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getPreviousMeleeWeaponHolding(),
            $this->getPreviousMeleeWeapon(),
            $shieldCode
        );
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
    private function addUsabilityToMeleeWeapons(array $meleeWeaponCodeValues): array
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
    private function addWeaponlikeUsability(array $weaponLikeCode, ItemHoldingCode $itemHoldingCode): array
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
    private function addUsabilityToRangedWeapons(array $rangedWeaponCodeValues): array
    {
        $meleeWeaponCodes = [];
        foreach ($rangedWeaponCodeValues as $rangedWeaponCodeValue) {
            $meleeWeaponCodes[] = RangedWeaponCode::getIt($rangedWeaponCodeValue);
        }

        return $this->addWeaponlikeUsability($meleeWeaponCodes, $this->getSelectedRangedWeaponHolding());
    }

    /**
     * @return array
     */
    public function getPossibleBodyArmors(): array
    {
        $bodyArmors = array_map(function (string $armorValue) {
            return BodyArmorCode::getIt($armorValue);
        }, BodyArmorCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($bodyArmors);
    }

    /**
     * @return array|HelmCode[][][]
     */
    public function getPossibleHelms(): array
    {
        $helmCodes = array_map(function (string $helmValue) {
            return HelmCode::getIt($helmValue);
        }, HelmCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($helmCodes);
    }

    /**
     * @return array|ShieldCode[][][]
     */
    public function getPossibleShields(): array
    {
        $shieldCodes = array_map(function (string $shieldValue) {
            return ShieldCode::getIt($shieldValue);
        }, ShieldCode::getPossibleValues());

        return $this->addNonWeaponArmamentUsability($shieldCodes);
    }

    /**
     * @param array|ArmamentCode[] $armamentCodes
     * @return array
     */
    private function addNonWeaponArmamentUsability(array $armamentCodes): array
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

    public function getSelectedBodyArmor(): BodyArmorCode
    {
        $selectedBodyArmorValue = $this->currentValues->getValue(Controller::BODY_ARMOR);
        if (!$selectedBodyArmorValue) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }
        $selectedBodyArmor = BodyArmorCode::getIt($selectedBodyArmorValue);
        if (!$this->canUseArmament($selectedBodyArmor, $this->currentProperties->getCurrentStrength())) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }

        return BodyArmorCode::getIt($selectedBodyArmorValue);
    }

    private function getPreviousBodyArmor(): BodyArmorCode
    {
        $previousBodyArmorValue = $this->history->getValue(Controller::BODY_ARMOR);
        if (!$previousBodyArmorValue) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }
        $previousBodyArmor = BodyArmorCode::getIt($previousBodyArmorValue);
        if (!$this->canUseArmament($previousBodyArmor, $this->previousProperties->getPreviousStrength())) {
            return BodyArmorCode::getIt(BodyArmorCode::WITHOUT_ARMOR);
        }

        return $previousBodyArmor;
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOfPreviousBodyArmor(): int
    {
        return Tables::getIt()->getBodyArmorsTable()->getProtectionOf($this->getPreviousBodyArmor());
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOfSelectedBodyArmor(): int
    {
        return $this->getProtectionOfBodyArmor($this->getSelectedBodyArmor());
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

    public function getSelectedHelm(): HelmCode
    {
        $selectedHelmValue = $this->currentValues->getValue(Controller::HELM);
        if (!$selectedHelmValue) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }
        $selectedHelm = HelmCode::getIt($selectedHelmValue);
        if (!$this->canUseArmament($selectedHelm, $this->currentProperties->getCurrentStrength())) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }

        return HelmCode::getIt($selectedHelmValue);
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getSelectedHelmProtection(): int
    {
        return $this->getProtectionOfHelm($this->getSelectedHelm());
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
    public function getSelectedShield(): ShieldCode
    {
        $selectedShieldValue = $this->currentValues->getValue(Controller::SHIELD);
        if (!$selectedShieldValue) {
            return ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD);
        }

        return ShieldCode::getIt($selectedShieldValue);
    }

    /**
     * @param ShieldCode|null $shield = null
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     */
    public function getSelectedRangedShieldHolding(ShieldCode $shield = null): ItemHoldingCode
    {
        return $this->getShieldHolding(
            $this->getSelectedRangedWeaponHolding(),
            $this->getSelectedRangedWeapon(),
            $shield
        );
    }

    private function getPreviousHelm(): HelmCode
    {
        $previousHelmValue = $this->history->getValue(Controller::HELM);
        if (!$previousHelmValue) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }
        $previousHelm = HelmCode::getIt($previousHelmValue);
        if (!$this->canUseArmament($previousHelm, $this->previousProperties->getPreviousStrength())) {
            return HelmCode::getIt(HelmCode::WITHOUT_HELM);
        }

        return $previousHelm;
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getPreviousHelmProtection(): int
    {
        return Tables::getIt()->getHelmsTable()->getProtectionOf($this->getPreviousHelm());
    }
}