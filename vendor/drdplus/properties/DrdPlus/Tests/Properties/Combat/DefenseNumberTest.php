<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Combat;

use DrdPlus\Properties\Combat\Defense;
use DrdPlus\Properties\Combat\DefenseNumber;
use DrdPlus\Tests\Properties\Combat\Partials\CharacteristicForGameTest;

class DefenseNumberTest extends CharacteristicForGameTest
{
    protected function createSut()
    {
        return DefenseNumber::getIt($this->createDefense(123));
    }

    /**
     * @test
     */
    public function I_can_get_property_easily()
    {
        $defenseNumber = DefenseNumber::getIt($this->createDefense(123));
        self::assertSame(123, $defenseNumber->getValue());
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Defense
     */
    private function createDefense($value)
    {
        $defense = \Mockery::mock(Defense::class);
        $defense->shouldReceive('getValue')
            ->andReturn($value);

        return $defense;
    }
}