<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\RemarkableSenses;

use DrdPlus\Properties\Partials\AbstractStringProperty;

/**
 * @method static RemarkableSenseProperty getEnum(string $enumValue)
 * @method static RemarkableSenseProperty getIt()
 */
abstract class RemarkableSenseProperty extends AbstractStringProperty
{

}