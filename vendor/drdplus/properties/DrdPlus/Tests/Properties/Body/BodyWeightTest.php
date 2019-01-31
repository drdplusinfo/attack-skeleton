<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Body\BodyWeight;
use DrdPlus\Properties\Body\BodyWeightInKg;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Weight\WeightBonus;
use DrdPlus\Tests\Properties\PropertyTest;

class BodyWeightTest extends PropertyTest
{
    use BodyPropertyTest;

    /**
     * @return string
     */
    protected function getExpectedCodeClass(): string
    {
        return PropertyCode::class;
    }

    /**
     * @test
     */
    public function I_can_get_property_easily(): void
    {
        $weight = $this->createWeight(123 /* weight bonus */, 456.789 /* weight in kg */);
        $bodyWeight = BodyWeight::getIt($weight);
        self::assertInstanceOf(BodyWeight::class, $bodyWeight);
        self::assertSame(111, $bodyWeight->getValue());
        self::assertSame('111', (string)$bodyWeight);
        self::assertSame(PropertyCode::getIt(PropertyCode::BODY_WEIGHT), $bodyWeight->getCode());
        self::assertSame($weight, $bodyWeight->getWeight());
        $weightBonus = $bodyWeight->getWeightBonus();
        self::assertInstanceOf(WeightBonus::class, $weightBonus);
        self::assertSame(123, $weightBonus->getValue());
        self::assertSame(BodyWeightInKg::getIt(456.789), $bodyWeight->getBodyWeightInKg());
    }

    /**
     * @param $weightBonusValue
     * @param $weightInKgValue
     * @return \Mockery\MockInterface|Weight
     */
    private function createWeight(int $weightBonusValue, float $weightInKgValue): Weight
    {
        $weightBonus = $this->mockery(WeightBonus::class);
        $weightBonus->shouldReceive('getValue')
            ->andReturn($weightBonusValue);
        $weight = $this->mockery(Weight::class);
        $weight->shouldReceive('getBonus')
            ->andReturn($weightBonus);
        $weight->shouldReceive('getKilograms')
            ->andReturn($weightInKgValue);

        return $weight;
    }
}