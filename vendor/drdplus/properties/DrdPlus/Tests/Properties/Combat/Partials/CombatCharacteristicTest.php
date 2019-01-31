<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Combat\Partials;

use DrdPlus\Codes\CombatCharacteristicCode;
use DrdPlus\Properties\Combat\Partials\CharacteristicForGame;
use DrdPlus\Tests\Properties\PropertyTest;
use Granam\Integer\IntegerInterface;

abstract class CombatCharacteristicTest extends PropertyTest
{
    /**
     * @return string
     */
    protected function getExpectedCodeClass()
    {
        return CombatCharacteristicCode::class;
    }

    /**
     * @test
     */
    public function I_can_use_it_as_integer_object()
    {
        $sut = $this->createSut();
        self::assertInstanceOf(IntegerInterface::class, $sut);
        self::assertSame((string)$sut->getValue(), (string)$sut);
        self::assertTrue(is_int($sut->getValue()));
    }

    /**
     * @return CharacteristicForGame
     */
    abstract protected function createSut();

}