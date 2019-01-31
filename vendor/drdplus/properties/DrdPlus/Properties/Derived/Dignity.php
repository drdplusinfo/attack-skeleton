<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Derived\Partials\AspectOfVisage;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Will;

class Dignity extends AspectOfVisage
{
    /**
     * @param Intelligence $intelligence
     * @param Will $will
     * @param Charisma $charisma
     * @return Dignity
     */
    public static function getIt(Intelligence $intelligence, Will $will, Charisma $charisma): Dignity
    {
        return new static($intelligence, $will, $charisma);
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::DIGNITY);
    }
}