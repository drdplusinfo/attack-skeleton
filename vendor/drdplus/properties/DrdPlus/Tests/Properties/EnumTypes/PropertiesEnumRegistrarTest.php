<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\EnumTypes;

use Doctrine\DBAL\Types\Type;
use Doctrineum\Scalar\ScalarEnumType;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\EnumTypes\PropertiesEnumRegistrar;
use DrdPlus\Properties\RemarkableSenses\EnumTypes\RemarkableSenseType;
use DrdPlus\Properties\RemarkableSenses\Hearing;
use DrdPlus\Properties\RemarkableSenses\Sight;
use DrdPlus\Properties\RemarkableSenses\Smell;
use DrdPlus\Properties\RemarkableSenses\Taste;
use DrdPlus\Properties\RemarkableSenses\Touch;
use Granam\Tests\Tools\TestWithMockery;

class PropertiesEnumRegistrarTest extends TestWithMockery
{

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $typeReflection = new \ReflectionClass(Type::class);
        $typesMap = $typeReflection->getProperty('_typesMap');
        $typesMap->setAccessible(true);
        $typesMap->setValue([]);

        $enumTypeReflection = new \ReflectionClass(ScalarEnumType::class);
        $subTypeMap = $enumTypeReflection->getProperty('enumSubTypesMap');
        $subTypeMap->setAccessible(true);
        $subTypeMap->setValue([]);
    }

    /**
     * @test
     */
    public function I_can_register_all_properties_as_doctrine_types(): void
    {
        PropertiesEnumRegistrar::registerAll();

        self::assertTrue(Type::hasType(PropertyCode::STRENGTH));
        self::assertTrue(Type::hasType(PropertyCode::AGILITY));
        self::assertTrue(Type::hasType(PropertyCode::KNACK));
        self::assertTrue(Type::hasType(PropertyCode::WILL));
        self::assertTrue(Type::hasType(PropertyCode::INTELLIGENCE));
        self::assertTrue(Type::hasType(PropertyCode::CHARISMA));

        self::assertTrue(Type::hasType(PropertyCode::HEIGHT_IN_CM));
        self::assertTrue(Type::hasType(PropertyCode::SIZE));
        self::assertTrue(Type::hasType(PropertyCode::BODY_WEIGHT_IN_KG));
        self::assertTrue(Type::hasType(PropertyCode::AGE));

        self::assertTrue(Type::hasType(PropertyCode::REMARKABLE_SENSE));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Touch::class));

        self::assertTrue(Type::hasType(PropertyCode::INFRAVISION));
        self::assertTrue(Type::hasType(PropertyCode::NATIVE_REGENERATION));
    }

    /**
     * @test
     */
    public function I_can_register_base_properties_as_doctrine_types(): void
    {
        PropertiesEnumRegistrar::registerBaseProperties();

        self::assertTrue(Type::hasType(PropertyCode::STRENGTH));
        self::assertTrue(Type::hasType(PropertyCode::AGILITY));
        self::assertTrue(Type::hasType(PropertyCode::KNACK));
        self::assertTrue(Type::hasType(PropertyCode::WILL));
        self::assertTrue(Type::hasType(PropertyCode::INTELLIGENCE));
        self::assertTrue(Type::hasType(PropertyCode::CHARISMA));

        self::assertFalse(Type::hasType(PropertyCode::HEIGHT_IN_CM));
        self::assertFalse(Type::hasType(PropertyCode::SIZE));
        self::assertFalse(Type::hasType(PropertyCode::BODY_WEIGHT_IN_KG));
        self::assertFalse(Type::hasType(PropertyCode::AGE));

        self::assertFalse(Type::hasType(PropertyCode::REMARKABLE_SENSE));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Touch::class));

        self::assertFalse(Type::hasType(PropertyCode::INFRAVISION));
        self::assertFalse(Type::hasType(PropertyCode::NATIVE_REGENERATION));
    }

    /**
     * @test
     */
    public function I_can_register_body_properties_as_doctrine_types(): void
    {
        PropertiesEnumRegistrar::registerBodyProperties();

        self::assertFalse(Type::hasType(PropertyCode::STRENGTH));
        self::assertFalse(Type::hasType(PropertyCode::AGILITY));
        self::assertFalse(Type::hasType(PropertyCode::KNACK));
        self::assertFalse(Type::hasType(PropertyCode::WILL));
        self::assertFalse(Type::hasType(PropertyCode::INTELLIGENCE));
        self::assertFalse(Type::hasType(PropertyCode::CHARISMA));

        self::assertTrue(Type::hasType(PropertyCode::HEIGHT_IN_CM));
        self::assertTrue(Type::hasType(PropertyCode::SIZE));
        self::assertTrue(Type::hasType(PropertyCode::BODY_WEIGHT_IN_KG));
        self::assertTrue(Type::hasType(PropertyCode::AGE));

        self::assertFalse(Type::hasType(PropertyCode::REMARKABLE_SENSE));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Touch::class));

        self::assertFalse(Type::hasType(PropertyCode::INFRAVISION));
        self::assertFalse(Type::hasType(PropertyCode::NATIVE_REGENERATION));
    }

    /**
     * @test
     */
    public function I_can_register_remarkable_senses_as_doctrine_type_and_enum_sub_types(): void
    {
        PropertiesEnumRegistrar::registerRemarkableSenses();

        self::assertFalse(Type::hasType(PropertyCode::STRENGTH));
        self::assertFalse(Type::hasType(PropertyCode::AGILITY));
        self::assertFalse(Type::hasType(PropertyCode::KNACK));
        self::assertFalse(Type::hasType(PropertyCode::WILL));
        self::assertFalse(Type::hasType(PropertyCode::INTELLIGENCE));
        self::assertFalse(Type::hasType(PropertyCode::CHARISMA));

        self::assertFalse(Type::hasType(PropertyCode::HEIGHT_IN_CM));
        self::assertFalse(Type::hasType(PropertyCode::SIZE));
        self::assertFalse(Type::hasType(PropertyCode::BODY_WEIGHT_IN_KG));
        self::assertFalse(Type::hasType(PropertyCode::AGE));

        self::assertTrue(Type::hasType(PropertyCode::REMARKABLE_SENSE));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Touch::class));

        self::assertFalse(Type::hasType(PropertyCode::INFRAVISION));
        self::assertFalse(Type::hasType(PropertyCode::NATIVE_REGENERATION));
    }

    /**
     * @test
     */
    public function I_can_register_native_properties_as_doctrine_type(): void
    {
        PropertiesEnumRegistrar::registerNativeProperties();

        self::assertFalse(Type::hasType(PropertyCode::STRENGTH));
        self::assertFalse(Type::hasType(PropertyCode::AGILITY));
        self::assertFalse(Type::hasType(PropertyCode::KNACK));
        self::assertFalse(Type::hasType(PropertyCode::WILL));
        self::assertFalse(Type::hasType(PropertyCode::INTELLIGENCE));
        self::assertFalse(Type::hasType(PropertyCode::CHARISMA));

        self::assertFalse(Type::hasType(PropertyCode::HEIGHT_IN_CM));
        self::assertFalse(Type::hasType(PropertyCode::SIZE));
        self::assertFalse(Type::hasType(PropertyCode::BODY_WEIGHT_IN_KG));
        self::assertFalse(Type::hasType(PropertyCode::AGE));

        self::assertFalse(Type::hasType(PropertyCode::REMARKABLE_SENSE));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertFalse(RemarkableSenseType::hasSubTypeEnum(Touch::class));

        self::assertTrue(Type::hasType(PropertyCode::INFRAVISION));
        self::assertTrue(Type::hasType(PropertyCode::NATIVE_REGENERATION));
    }
}