<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\Calculators;

use DrdPlus\Calculators\AttackSkeleton\AttackForCalculator;
use DrdPlus\Calculators\AttackSkeleton\CurrentValues;
use DrdPlus\Calculators\AttackSkeleton\CustomArmamentsService;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Configurator\Skeleton\History;
use DrdPlus\Configurator\Skeleton\Memory;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Body\Size;
use DrdPlus\Tables\Tables;
use Granam\Tests\Tools\TestWithMockery;

class AttackForCalculatorTest extends TestWithMockery
{
    /**
     * @test
     * @runInSeparateProcess because of cookies
     */
    public function I_can_use_it(): void
    {
        $attackForCalculator = new AttackForCalculator(
            new CurrentValues([], new Memory(true, [], true, static::class)),
            new History(true, [], true, static::class),
            new CustomArmamentsService(),
            Tables::getIt()
        );
        $this->I_get_main_hand_as_default_item_holding($attackForCalculator);
        $this->I_get_bare_hands_as_default_melee_weapon($attackForCalculator);
        $this->I_get_all_armor_codes_with_their_usability($attackForCalculator);
    }

    /**
     * @param AttackForCalculator $attackForCalculator
     */
    private function I_get_main_hand_as_default_item_holding(AttackForCalculator $attackForCalculator): void
    {
        self::assertSame(ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND), $attackForCalculator->getCurrentMeleeWeaponHolding());
    }

    /**
     * @param AttackForCalculator $attackForCalculator
     */
    private function I_get_bare_hands_as_default_melee_weapon(AttackForCalculator $attackForCalculator): void
    {
        self::assertSame(MeleeWeaponCode::getIt(MeleeWeaponCode::HAND), $attackForCalculator->getCurrentMeleeWeapon());
    }

    /**
     * @param AttackForCalculator $attackForCalculator
     */
    private function I_get_all_armor_codes_with_their_usability(AttackForCalculator $attackForCalculator): void
    {
        $bodyArmors = $attackForCalculator->getPossibleBodyArmors();
        self::assertCount(\count(BodyArmorCode::getPossibleValues()), $bodyArmors);
        $bodyArmorValues = BodyArmorCode::getPossibleValues();
        $strength = Strength::getIt(0);
        $size = Size::getIt(0);
        foreach ($bodyArmors as $index => $bodyArmorWithUsability) {
            /** @var BodyArmorCode $bodyArmor */
            $bodyArmor = $bodyArmorWithUsability['code'];
            self::assertContains($bodyArmor->getValue(), $bodyArmorValues);
            unset($bodyArmors[$index], $bodyArmorValues[\array_search($bodyArmor->getValue(), $bodyArmorValues, true)]);
            self::assertSame(
                Tables::getIt()->getArmourer()->canUseArmament($bodyArmor, $strength, $size),
                $bodyArmorWithUsability['canUseIt'],
                "Armor {$bodyArmor} has opposite usability with zero strength and body size"
            );
        }
        self::assertCount(0, $bodyArmorValues, 'There are some body armors missed by the ' . self::getSutClass());
        self::assertCount(0, $bodyArmors, 'There are some non-existing body armors given by the ' . self::getSutClass());
    }
}