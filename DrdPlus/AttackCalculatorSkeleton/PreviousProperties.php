<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\AttackCalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Properties\Body\HeightInCm;
use DrdPlus\Properties\Body\Size;
use Granam\Strict\Object\StrictObject;

class PreviousProperties extends StrictObject
{
    /** @var History */
    private $history;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function getPreviousStrength(): Strength
    {
        return Strength::getIt((int)$this->history->getValue(Controller::STRENGTH));
    }

    public function getPreviousAgility(): Agility
    {
        return Agility::getIt((int)$this->history->getValue(Controller::AGILITY));
    }

    public function getPreviousKnack(): Knack
    {
        return Knack::getIt((int)$this->history->getValue(Controller::KNACK));
    }

    public function getPreviousWill(): Will
    {
        return Will::getIt((int)$this->history->getValue(Controller::WILL));
    }

    public function getPreviousIntelligence(): Intelligence
    {
        return Intelligence::getIt((int)$this->history->getValue(Controller::INTELLIGENCE));
    }

    public function getPreviousCharisma(): Charisma
    {
        return Charisma::getIt((int)$this->history->getValue(Controller::CHARISMA));
    }

    public function getPreviousSize(): Size
    {
        return Size::getIt((int)$this->history->getValue(Controller::SIZE));
    }

    public function getPreviousHeightInCm(): HeightInCm
    {
        return HeightInCm::getIt($this->history->getValue(Controller::HEIGHT_IN_CM) ?? 150);
    }

}