<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Combat;

use DrdPlus\Codes\Properties\CharacteristicForGameCode;
use DrdPlus\Properties\Combat\Partials\CharacteristicForGame;

/**
 * See PPH page 92 right column, @link https://pph.drdplus.info/#utocne_cislo_uc
 * Attack number can be affected by many ways unlike Attack.
 *
 * @method AttackNumber add(int | \Granam\Integer\IntegerInterface $value)
 * @method AttackNumber sub(int | \Granam\Integer\IntegerInterface $value)
 */
class AttackNumber extends CharacteristicForGame
{
    /**
     * @param Attack $attack
     * @return AttackNumber
     */
    public static function getItFromAttack(Attack $attack): AttackNumber
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static($attack->getValue());
    }

    /**
     * @param Shooting $shooting
     * @return AttackNumber
     */
    public static function getItFromShooting(Shooting $shooting): AttackNumber
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new static($shooting->getValue());
    }

    /**
     * @return CharacteristicForGameCode
     */
    public function getCode(): CharacteristicForGameCode
    {
        return CharacteristicForGameCode::getIt(CharacteristicForGameCode::ATTACK_NUMBER);
    }
}