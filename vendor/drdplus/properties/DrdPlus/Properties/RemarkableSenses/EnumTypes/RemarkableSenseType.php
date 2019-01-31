<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\RemarkableSenses\EnumTypes;

use Doctrineum\Scalar\ScalarEnumType;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\RemarkableSenses\Hearing;
use DrdPlus\Properties\RemarkableSenses\Sight;
use DrdPlus\Properties\RemarkableSenses\Smell;
use DrdPlus\Properties\RemarkableSenses\Taste;
use DrdPlus\Properties\RemarkableSenses\Touch;

class RemarkableSenseType extends ScalarEnumType
{
    const REMARKABLE_SENSE = 'remarkable_sense';

    public static function registerSenses()
    {
        self::registerSelf();

        // Remarkable senses are enum subtypes - one column for one and only one remarkable sense
        if (!self::hasSubTypeEnum(Hearing::class)) {
            self::addSubTypeEnum(Hearing::class, '~' . PropertyCode::HEARING . '~' /* regexp */);
        }
        if (!self::hasSubTypeEnum(Sight::class)) {
            self::addSubTypeEnum(Sight::class, '~' . PropertyCode::SIGHT . '~' /* regexp */);
        }
        if (!self::hasSubTypeEnum(Smell::class)) {
            self::addSubTypeEnum(Smell::class, '~' . PropertyCode::SMELL . '~' /* regexp */);
        }
        if (!self::hasSubTypeEnum(Taste::class)) {
            self::addSubTypeEnum(Taste::class, '~' . PropertyCode::TASTE . '~' /* regexp */);
        }
        if (!self::hasSubTypeEnum(Touch::class)) {
            self::addSubTypeEnum(Touch::class, '~' . PropertyCode::TOUCH . '~' /* regexp */);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::REMARKABLE_SENSE;
    }
}
