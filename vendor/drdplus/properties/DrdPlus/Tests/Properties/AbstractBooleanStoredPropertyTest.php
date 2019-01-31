<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties;

use DrdPlus\Properties\Native\NativeProperty;

abstract class AbstractBooleanStoredPropertyTest extends AbstractStoredPropertyTest
{
    /**
     * @return bool[]
     */
    protected function getValuesForTest(): array
    {
        return [true, false];
    }

    /**
     * @test
     */
    public function I_can_get_history_of_its_creation()
    {
        /** @var NativeProperty $propertyClass */
        $propertyClass = self::getSutClass();
        $property = false;
        foreach ($this->getValuesForTest() as $value) {
            /** @var NativeProperty $property */
            $property = $propertyClass::getIt($value);
            self::assertEquals(
                [['changeBy' => ['name' => 'i can get history of its creation', 'with' => ''], 'result' => $value]],
                $property->getHistory()
            );
        }

        return $property;
    }
}