<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton\Web;

use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\ArmamentsUsabilityMessages;
use DrdPlus\AttackSkeleton\CurrentArmaments;
use DrdPlus\AttackSkeleton\CurrentArmamentsValues;
use DrdPlus\AttackSkeleton\CustomArmamentsState;
use DrdPlus\AttackSkeleton\FrontendHelper;
use DrdPlus\AttackSkeleton\PossibleArmaments;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\Web\BodyInterface;

abstract class AbstractArmamentBody extends StrictObject implements BodyInterface
{
    use ArmamentUsabilityTrait;

    /** @var CustomArmamentsState */
    protected $customArmamentsState;
    /** @var CurrentArmamentsValues */
    protected $currentArmamentsValues;
    /** @var CurrentArmaments */
    protected $currentArmaments;
    /** @var ArmamentsUsabilityMessages */
    protected $armamentsUsabilityMessages;
    /** @var FrontendHelper */
    protected $frontendHelper;
    /** @var PossibleArmaments */
    protected $possibleArmaments;
    /** @var Armourer */
    protected $armourer;

    public function __construct(
        CustomArmamentsState $customArmamentsState,
        CurrentArmaments $currentArmaments,
        CurrentArmamentsValues $currentArmamentsValues,
        PossibleArmaments $possibleArmaments,
        ArmamentsUsabilityMessages $armamentsUsabilityMessages,
        FrontendHelper $frontendHelper,
        Armourer $armourer
    )
    {
        $this->customArmamentsState = $customArmamentsState;
        $this->currentArmaments = $currentArmaments;
        $this->currentArmamentsValues = $currentArmamentsValues;
        $this->armamentsUsabilityMessages = $armamentsUsabilityMessages;
        $this->frontendHelper = $frontendHelper;
        $this->possibleArmaments = $possibleArmaments;
        $this->armourer = $armourer;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}