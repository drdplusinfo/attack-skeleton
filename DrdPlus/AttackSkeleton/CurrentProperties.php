<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\AttackSkeleton;

use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Properties\Body\HeightInCm;
use DrdPlus\Properties\Body\Size;
use Granam\Strict\Object\StrictObject;

class CurrentProperties extends StrictObject
{
    /** @var CurrentAttackValues */
    private $currentAttackValues;

    public function __construct(CurrentAttackValues $currentAttackValues)
    {
        $this->currentAttackValues = $currentAttackValues;
    }

    public function getCurrentStrength(): Strength
    {
        return Strength::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::STRENGTH));
    }

    public function getCurrentAgility(): Agility
    {
        return Agility::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::AGILITY));
    }

    public function getCurrentKnack(): Knack
    {
        return Knack::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::KNACK));
    }

    public function getCurrentWill(): Will
    {
        return Will::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::WILL));
    }

    public function getCurrentIntelligence(): Intelligence
    {
        return Intelligence::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::INTELLIGENCE));
    }

    public function getCurrentCharisma(): Charisma
    {
        return Charisma::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::CHARISMA));
    }

    public function getCurrentSize(): Size
    {
        return Size::getIt((int)$this->currentAttackValues->getCurrentValue(AttackController::SIZE));
    }

    public function getCurrentHeightInCm(): HeightInCm
    {
        return HeightInCm::getIt($this->currentAttackValues->getCurrentValue(AttackController::HEIGHT_IN_CM) ?? 150);
    }

}