<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Combat;

use DrdPlus\Properties\Combat\Attack;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Tests\Properties\Combat\Partials\CombatCharacteristicTest;

class AttackTest extends CombatCharacteristicTest
{
    protected function createSut()
    {
        return Attack::getIt($this->createAgility(123));
    }

    /**
     * @test
     */
    public function I_can_get_property_easily()
    {
        for ($value = -5; $value < 10; $value++) {
            $attack = Attack::getIt($this->createAgility($value));
            self::assertSame((int)floor($value / 2), $attack->getValue());
        }
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Agility
     */
    private function createAgility($value)
    {
        $agility = \Mockery::mock(Agility::class);
        $agility->shouldReceive('getValue')
            ->andReturn($value);

        return $agility;
    }
}