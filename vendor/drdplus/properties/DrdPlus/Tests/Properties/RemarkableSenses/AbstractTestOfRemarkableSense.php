<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\RemarkableSenses;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\RemarkableSenses\EnumTypes\RemarkableSenseType;
use DrdPlus\Properties\RemarkableSenses\RemarkableSenseProperty;
use DrdPlus\Tests\Properties\AbstractStoredPropertyTest;

abstract class AbstractTestOfRemarkableSense extends AbstractStoredPropertyTest
{
    /**
     * @test
     * @return RemarkableSenseProperty
     */
    public function I_can_get_property_easily(): RemarkableSenseProperty
    {
        $propertyClass = self::getSutClass();
        /** @var RemarkableSenseProperty $propertyClass */
        $property = $propertyClass::getIt();
        self::assertInstanceOf($propertyClass, $property);
        self::assertSame(strtolower($this->getSutBaseName()), $property->getValue());
        self::assertSame(PropertyCode::getIt(strtolower($this->getSutBaseName())), $property->getCode());

        return $property;
    }

    protected function getValuesForTest(): array
    {
        throw new \LogicException('Should not be called');
    }

    /**
     * @test
     */
    public function I_can_register_it_as_enum()
    {
        RemarkableSenseType::registerSenses();
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(self::getSutClass()));
    }
}