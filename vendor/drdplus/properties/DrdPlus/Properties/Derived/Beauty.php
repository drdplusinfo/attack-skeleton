<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Derived\Partials\AspectOfVisage;
use DrdPlus\Properties\Base\Knack;

class Beauty extends AspectOfVisage
{
    /**
     * @param Agility $agility
     * @param Knack $knack
     * @param Charisma $charisma
     * @return Beauty
     */
    public static function getIt(Agility $agility, Knack $knack, Charisma $charisma): Beauty
    {
        return new static($agility, $knack, $charisma);
    }

    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::BEAUTY);
    }
}