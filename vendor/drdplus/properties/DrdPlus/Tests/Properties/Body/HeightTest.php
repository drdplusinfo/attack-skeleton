<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Body;

use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Body\Height;
use DrdPlus\Properties\Body\HeightInCm;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Properties\PropertyTest;

class HeightTest extends PropertyTest
{
    use BodyPropertyTest;

    /**
     * @return string
     */
    protected function getExpectedCodeClass()
    {
        return PropertyCode::class;
    }

    /**
     * @test
     */
    public function I_can_get_property_easily()
    {
        $tables = $this->createTablesWithDistanceTable(
            function (Distance $distance) {
                self::assertSame(1.23, $distance->getValue());
                self::assertSame(DistanceUnitCode::METER, $distance->getUnit());

                return $this->createDistanceBonus(456);
            }
        );
        $height = Height::getIt($this->createHeightInCm(123), $tables);
        self::assertInstanceOf(Height::class, $height);
        self::assertSame(456, $height->getValue());
        self::assertSame('456', (string)$height);
        self::assertSame(PropertyCode::getIt($this->getExpectedPropertyCode()), $height->getCode());
    }

    /**
     * @param int $value
     * @return HeightInCm|\Mockery\MockInterface
     */
    protected function createValueForProperty($value)
    {
        return $this->createHeightInCm($value);
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|HeightInCm
     */
    private function createHeightInCm($value)
    {
        $heightInCm = $this->mockery(HeightInCm::class);
        $heightInCm->shouldReceive('getValue')
            ->andReturn($value);

        return $heightInCm;
    }

    /**
     * @param \Closure $toBonus
     * @return \Mockery\MockInterface|Tables
     */
    private function createTablesWithDistanceTable(\Closure $toBonus)
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getDistanceTable')
            ->andReturn($distanceTable = $this->mockery(DistanceTable::class));
        $distanceTable->shouldReceive('toBonus')
            ->zeroOrMoreTimes()
            ->andReturnUsing($toBonus);

        return $tables;
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|DistanceBonus
     */
    private function createDistanceBonus($value)
    {
        $distanceBonus = $this->mockery(DistanceBonus::class);
        $distanceBonus->shouldReceive('getValue')
            ->andReturn($value);

        return $distanceBonus;
    }
}
