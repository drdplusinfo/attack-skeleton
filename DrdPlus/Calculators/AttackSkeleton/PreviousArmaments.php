<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Configurator\Skeleton\History;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Tables;
use Granam\Strict\Object\StrictObject;

class PreviousArmaments extends StrictObject
{
    /** @var History */
    private $history;
    /** @var PreviousProperties */
    private $previousProperties;

    public function __construct(History $history, PreviousProperties $previousProperties)
    {
        $this->history = $history;
        $this->previousProperties = $previousProperties;
    }

    /**
     * @return MeleeWeaponCode
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function getPreviousMeleeWeapon(): MeleeWeaponCode
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
     * @param ItemHoldingCode $itemHoldingCode
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByOneHand
     * @throws \DrdPlus\Tables\Armaments\Exceptions\CanNotHoldWeaponByTwoHands
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    private function couldUseWeaponlike(WeaponlikeCode $weaponlikeCode, ItemHoldingCode $itemHoldingCode): bool
    {
        return $this->couldUseArmament(
            $weaponlikeCode,
            Tables::getIt()->getArmourer()->getStrengthForWeaponOrShield(
                $weaponlikeCode,
                $this->getWeaponHolding($weaponlikeCode, $itemHoldingCode->getValue()),
                $this->previousProperties->getPreviousStrength()
            )
        );
    }

    /**
     * @param ArmamentCode $armamentCode
     * @param Strength $strengthForArmament
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    private function couldUseArmament(ArmamentCode $armamentCode, Strength $strengthForArmament): bool
    {
        return Tables::getIt()->getArmourer()
            ->canUseArmament(
                $armamentCode,
                $strengthForArmament,
                $this->previousProperties->getPreviousSize()
            );
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
    public function isOneHandedOnly(WeaponlikeCode $weaponlikeCode): bool
    {
        return Tables::getIt()->getArmourer()->isOneHandedOnly($weaponlikeCode);
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
}