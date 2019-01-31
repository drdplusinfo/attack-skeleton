<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Combat\Partials;

use DrdPlus\Codes\Properties\CharacteristicForGameCode;

abstract class CharacteristicForGameTest extends CombatCharacteristicTest
{
    /**
     * @return string
     */
    protected function getExpectedCodeClass()
    {
        return CharacteristicForGameCode::class;
    }

    /**
     * @test
     */
    public function I_can_add_value()
    {
        $combatCharacteristic = $this->createSut();
        $expectedPropertyHistory = [
            [
                'changeBy' => $this->getExpectedInitialChangeBy(),
                'result' => $combatCharacteristic->getValue(),
            ],
        ];
        self::assertEquals($expectedPropertyHistory, $combatCharacteristic->getHistory());

        $increased = $combatCharacteristic->add(456);
        self::assertNotEquals($combatCharacteristic, $increased);
        self::assertSame($combatCharacteristic->getValue() + 456, $increased->getValue());
        $expectedPropertyHistory[] = [
            'changeBy' => ['name' => 'i can add value', 'with' => ''],
            'result' => $increased->getValue(),
        ];
        self::assertEquals($expectedPropertyHistory, $increased->getHistory());

        $double = $increased->add($increased);
        self::assertSame($increased->getValue() * 2, $double->getValue());
        $expectedPropertyHistory[] = [
            'changeBy' => ['name' => 'i can add value', 'with' => ''],
            'result' => $double->getValue(),
        ];
        self::assertEquals($expectedPropertyHistory, $double->getHistory());
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedInitialChangeBy()
    {
        return ['name' => 'create sut', 'with' => ''];
    }

    /**
     * @test
     */
    public function I_can_subtract_value()
    {
        $combatCharacteristic = $this->createSut();
        $expectedPropertyHistory = [
            [
                'changeBy' => $this->getExpectedInitialChangeBy(),
                'result' => $combatCharacteristic->getValue(),
            ],
        ];
        self::assertEquals($expectedPropertyHistory, $combatCharacteristic->getHistory());

        $decreased = $combatCharacteristic->sub(1);
        self::assertNotEquals($combatCharacteristic, $decreased);
        self::assertSame($combatCharacteristic->getValue() - 1, $decreased->getValue());
        $expectedDecreasedHistory = $expectedPropertyHistory;
        $expectedDecreasedHistory[] = [
            'changeBy' => ['name' => 'i can subtract value', 'with' => ''],
            'result' => $decreased->getValue(),
        ];
        self::assertEquals($expectedDecreasedHistory, $decreased->getHistory());

        $zeroed = $decreased->sub($decreased);
        self::assertSame(0, $zeroed->getValue());
        $expectedZeroedHistory = $expectedDecreasedHistory;
        $expectedZeroedHistory[] = [
            'changeBy' => ['name' => 'i can subtract value', 'with' => ''],
            'result' => $zeroed->getValue(),
        ];
        self::assertEquals($expectedZeroedHistory, $zeroed->getHistory());
    }

    /**
     * @test
     */
    public function Its_modifying_methods_have_return_value_annotated()
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBasename = preg_replace('~^.+[\\\](\w+)$~', '$1', self::getSutClass());
        self::assertContains(<<<ANNOTATION
 * @method {$classBasename} add(int | \\Granam\\Integer\\IntegerInterface \$value)
 * @method {$classBasename} sub(int | \\Granam\\Integer\\IntegerInterface \$value)
ANNOTATION
            , (string)$reflectionClass->getDocComment());
    }
}