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
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomBodyArmorBody;
use DrdPlus\Codes\Armaments\BodyArmorCode;

class BodyArmorBody extends AbstractArmamentBody
{
    /** @var AddCustomBodyArmorBody */
    private $addCustomBodyArmorBody;

    public function __construct(
        CustomArmamentsState $customArmamentsState,
        CurrentArmaments $currentArmaments,
        CurrentArmamentsValues $currentArmamentsValues,
        PossibleArmaments $possibleArmaments,
        ArmamentsUsabilityMessages $armamentsUsabilityMessages,
        FrontendHelper $frontendHelper,
        Armourer $armourer,
        AddCustomBodyArmorBody $addCustomBodyArmorBody
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
        $this->addCustomBodyArmorBody = $addCustomBodyArmorBody;
    }

    public function getValue(): string
    {

        return <<<HTML
{$this->getAddCustomBodyArmor()}
{$this->getCurrentCustomBodyArmors()}
<div class="row {$this->getVisibilityClass()}" id="chooseBodyArmor">
  <div class="col">
    <div class="messages">
      {$this->getMessagesAboutBodyArmors()}
    </div>
    <a title="Přidat vlastní zbroj" href="{$this->getUrlToAddNewBodyArmor()}" class="button add">+</a>
    <label>
      <select name="{$this->getBodyArmorSelectName()}" title="Zbroj">
          {$this->getPossibleBodyArmors()}
      </select>
    </label>
  </div>
</div>
HTML;
    }

    private function getAddCustomBodyArmor(): string
    {
        if (!$this->customArmamentsState->isAddingNewBodyArmor()) {
            return '';
        }

        return <<<HTML
<div id="addBodyArmor" class="row add">
  {$this->addCustomBodyArmorBody->getValue()}
</div>
HTML;
    }

    private function getCurrentCustomBodyArmors(): string
    {
        $possibleCustomBodyArmors = '';
        foreach ($this->currentArmamentsValues->getCurrentCustomBodyArmorsValues() as $armorName => $armorValues) {
            /** @var array|string[] $armorValues */
            foreach ($armorValues as $typeName => $armorValue) {
                $possibleCustomBodyArmors .= <<<HTML
<input type="hidden" name="{$typeName}[{$armorName}]" value="<{$armorValue}">
HTML;
            }
        }

        return $possibleCustomBodyArmors;
    }

    private function getBodyArmorSelectName(): string
    {
        return FrontendHelper::BODY_ARMOR;
    }

    private function getVisibilityClass(): string
    {
        return $this->customArmamentsState->isAddingNewBodyArmor()
            ? 'hidden'
            : '';
    }

    private function getMessagesAboutBodyArmors(): string
    {
        $messagesAboutBodyArmors = '';
        foreach ($this->armamentsUsabilityMessages->getMessagesAboutBodyArmors() as $messageAboutBodyArmor) {
            $messagesAboutBodyArmors .= <<<HTML
<div class="info">{$messageAboutBodyArmor}</div>
HTML;
        }

        return $messagesAboutBodyArmors;
    }

    private function getUrlToAddNewBodyArmor(): string
    {
        return $this->frontendHelper->getLocalUrlWithQuery([FrontendHelper::ACTION => FrontendHelper::ADD_NEW_BODY_ARMOR]);
    }

    private function getPossibleBodyArmors(): string
    {
        $bodyArmors = '';
        foreach ($this->possibleArmaments->getPossibleBodyArmors() as $possibleBodyArmor) {
            /** @var BodyArmorCode $bodyArmorCode */
            $bodyArmorCode = $possibleBodyArmor['code'];
            $bodyArmors .= <<<HTML
<option value="{$bodyArmorCode->getValue()}" {$this->getSelected($bodyArmorCode)} {$this->getDisabled($possibleBodyArmor['canUseIt'])}>
  {$this->getUsabilityPictogram($possibleBodyArmor['canUseIt'])}{$bodyArmorCode->translateTo('cs')} {$this->getBodyArmorProtection($bodyArmorCode)}
</option>
HTML;
        }

        return $bodyArmors;
    }

    private function getSelected(BodyArmorCode $bodyArmorCode): string
    {
        return $this->currentArmaments->getCurrentBodyArmor()->getValue() === $bodyArmorCode->getValue()
            ? 'selected'
            : '';
    }

    private function getBodyArmorProtection(BodyArmorCode $bodyArmorCode): string
    {
        return $this->frontendHelper->formatInteger($this->armourer->getProtectionOfBodyArmor($bodyArmorCode));
    }
}