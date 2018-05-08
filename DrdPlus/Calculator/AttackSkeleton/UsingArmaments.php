<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Calculator\AttackSkeleton;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;

trait UsingArmaments
{

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param ItemHoldingCode $itemHoldingCode
     * @param PreviousProperties $previousProperties
     * @param Tables $tables
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    protected function couldUseWeaponlike(
        WeaponlikeCode $weaponlikeCode,
        ItemHoldingCode $itemHoldingCode,
        PreviousProperties $previousProperties,
        Tables $tables
    ): bool
    {
        return $this->couldUseArmament(
            $weaponlikeCode,
            $tables->getArmourer()->getStrengthForWeaponOrShield(
                $weaponlikeCode,
                $this->getWeaponlikeHolding($weaponlikeCode, $itemHoldingCode->getValue(), $tables),
                $previousProperties->getPreviousStrength()
            ),
            $previousProperties,
            $tables
        );
    }

    /**
     * @param ArmamentCode $armamentCode
     * @param Strength $strengthForArmament
     * @param PreviousProperties $previousProperties
     * @param Tables $tables
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    protected function couldUseArmament(
        ArmamentCode $armamentCode,
        Strength $strengthForArmament,
        PreviousProperties $previousProperties,
        Tables $tables
    ): bool
    {
        return $tables->getArmourer()
            ->canUseArmament(
                $armamentCode,
                $strengthForArmament,
                $previousProperties->getPreviousSize()
            );
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param string $weaponHolding
     * @param Tables $tables
     * @return ItemHoldingCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    protected function getWeaponlikeHolding(WeaponlikeCode $weaponlikeCode, string $weaponHolding, Tables $tables): ItemHoldingCode
    {
        if ($tables->getArmourer()->isTwoHandedOnly($weaponlikeCode)) {
            return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
        }
        if ($tables->getArmourer()->isOneHandedOnly($weaponlikeCode)) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }
        if (!$weaponHolding) {
            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }

        return ItemHoldingCode::getIt($weaponHolding);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param Tables $tables
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function isOneHandedOnly(WeaponlikeCode $weaponlikeCode, Tables $tables): bool
    {
        return $tables->getArmourer()->isOneHandedOnly($weaponlikeCode);
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @param Tables $tables
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function isTwoHandedOnly(WeaponlikeCode $weaponlikeCode, Tables $tables): bool
    {
        return $tables->getArmourer()->isTwoHandedOnly($weaponlikeCode);
    }

    /**
     * @param ItemHoldingCode $weaponHolding
     * @param WeaponlikeCode $weaponlikeCode
     * @param ShieldCode $shield
     * @param Tables $tables
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    public function getShieldHolding(
        ItemHoldingCode $weaponHolding,
        WeaponlikeCode $weaponlikeCode,
        ShieldCode $shield,
        Tables $tables
    ): ItemHoldingCode
    {
        if ($weaponHolding->holdsByTwoHands()) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            if ($tables->getArmourer()->canHoldItByTwoHands($shield)) {
                // because two-handed weapon has to be dropped to use shield and then both hands can be used for shield
                return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
            }

            return ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if ($weaponlikeCode->isUnarmed() && $tables->getArmourer()->canHoldItByTwoHands($shield)) {
            return ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS);
        }

        return $weaponHolding->getOpposite();
    }
}