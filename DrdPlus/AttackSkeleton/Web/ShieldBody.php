<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton\Web;

use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\ArmamentsUsabilityMessages;
use DrdPlus\AttackSkeleton\AttackRequest;
use DrdPlus\AttackSkeleton\CurrentArmaments;
use DrdPlus\AttackSkeleton\CurrentArmamentsValues;
use DrdPlus\AttackSkeleton\CustomArmamentsState;
use DrdPlus\AttackSkeleton\FrontendHelper;
use DrdPlus\AttackSkeleton\PossibleArmaments;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomShieldBody;
use DrdPlus\Codes\Armaments\ShieldCode;

class ShieldBody extends AbstractArmamentBody
{
    /** @var AddCustomShieldBody */
    private $addCustomShieldBody;

    public function __construct(
        CustomArmamentsState $customArmamentsState,
        CurrentArmaments $currentArmaments,
        CurrentArmamentsValues $currentArmamentsValues,
        PossibleArmaments $possibleArmaments,
        ArmamentsUsabilityMessages $armamentsUsabilityMessages,
        FrontendHelper $frontendHelper,
        Armourer $armourer,
        AddCustomShieldBody $addCustomShieldBody
    )
    {
        parent::__construct(
            $customArmamentsState,
            $currentArmaments,
            $currentArmamentsValues,
            $possibleArmaments,
            $armamentsUsabilityMessages,
            $frontendHelper,
            $armourer
        );
        $this->addCustomShieldBody = $addCustomShieldBody;
    }

    public function getValue(): string
    {
        return <<<HTML
{$this->getAddShield()}
{$this->getCurrentCustomShields()}
<div class="row {$this->getVisibilityClass()}" id="chooseShield">
  <div class="col">
    <div class="messages">
        {$this->getMessagesAboutShields()}
    </div>
    <a title="Přidat vlastní štít" href="{$this->getLinkToAddNewShield()}" class="button add">+</a>
    <label>
      <select name="{$this->getShieldSelectName()}" title="Štít">
         {$this->getPossibleShields()} 
      </select>
    </label>
  </div>
</div>
HTML;
    }

    private function getPossibleShields(): string
    {
        $shields = '';
        foreach ($this->possibleArmaments->getPossibleShields() as $possibleShield) {
            /** @var ShieldCode $shieldCode */
            $shieldCode = $possibleShield['code'];
            $shields .= <<<HTML
<option value="{$shieldCode->getValue()}" {$this->getShieldSelected($shieldCode)} {$this->getDisabled($possibleShield['canUseIt'])}>
  {$this->getUsabilityPictogram($possibleShield['canUseIt'])}{$shieldCode->translateTo('cs')} {$this->getShieldProtection($shieldCode)}
</option>
HTML;
        }

        return $shields;
    }

    private function getShieldProtection(ShieldCode $shieldCode): string
    {
        return $this->frontendHelper->formatInteger($this->armourer->getCoverOfShield($shieldCode));
    }

    private function getShieldSelected(ShieldCode $shieldCode): string
    {
        return $this->getSelected($this->currentArmaments->getCurrentShield()->getValue(), $shieldCode->getValue());
    }

    private function getShieldSelectName(): string
    {
        return AttackRequest::SHIELD;
    }

    private function getLinkToAddNewShield(): string
    {
        return $this->frontendHelper->getLocalUrlToAction(AttackRequest::ADD_NEW_SHIELD);
    }

    private function getVisibilityClass(): string
    {
        return $this->customArmamentsState->isAddingNewShield()
            ? 'hidden'
            : '';
    }

    private function getMessagesAboutShields(): string
    {
        $messagesAboutShields = '';
        foreach ($this->armamentsUsabilityMessages->getMessagesAboutShields() as $messageAboutShield) {
            $messagesAboutShields .= <<<HTML
          <div class="info">$messageAboutShield</div>
HTML;
        }

        return $messagesAboutShields;
    }

    private function getAddShield(): string
    {
        if (!$this->customArmamentsState->isAddingNewShield()) {
            return '';
        }

        return <<<HTML
<div id="addShield" class="row add">
  {$this->addCustomShieldBody->getValue()}
</div>
HTML;
    }

    private function getCurrentCustomShields(): string
    {
        $possibleCustomShields = '';
        foreach ($this->currentArmamentsValues->getCurrentCustomShieldsValues() as $armorName => $armorValues) {
            /** @var array|string[] $armorValues */
            foreach ($armorValues as $typeName => $armorValue) {
                $possibleCustomShields .= <<<HTML
<input type="hidden" name="{$typeName}[{$armorName}]" value="<{$armorValue}">
HTML;
            }
        }

        return $possibleCustomShields;
    }
}