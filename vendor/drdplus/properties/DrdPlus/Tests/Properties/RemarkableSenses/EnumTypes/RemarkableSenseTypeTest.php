<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\RemarkableSenses\EnumTypes;

use Doctrine\DBAL\Types\Type;
use DrdPlus\Properties\RemarkableSenses\EnumTypes\RemarkableSenseType;
use DrdPlus\Properties\RemarkableSenses\Hearing;
use DrdPlus\Properties\RemarkableSenses\Sight;
use DrdPlus\Properties\RemarkableSenses\Smell;
use DrdPlus\Properties\RemarkableSenses\Taste;
use DrdPlus\Properties\RemarkableSenses\Touch;
use Granam\Tests\Tools\TestWithMockery;

class RemarkableSenseTypeTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_register_all_remarkable_senses_at_once()
    {
        RemarkableSenseType::registerSenses();

        self::assertTrue(Type::hasType(RemarkableSenseType::REMARKABLE_SENSE));

        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Hearing::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Sight::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Smell::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Taste::class));
        self::assertTrue(RemarkableSenseType::hasSubTypeEnum(Touch::class));
    }
}
