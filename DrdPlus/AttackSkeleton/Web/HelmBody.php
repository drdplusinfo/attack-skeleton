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
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomHelmBody;
use DrdPlus\Codes\Armaments\HelmCode;

class HelmBody extends AbstractArmamentBody
{
    /** @var AddCustomHelmBody */
    private $addCustomHelmBody;

    public function __construct(
        CustomArmamentsState $customArmamentsState,
        CurrentArmaments $currentArmaments,
        CurrentArmamentsValues $currentArmamentsValues,
        PossibleArmaments $possibleArmaments,
        ArmamentsUsabilityMessages $armamentsUsabilityMessages,
        FrontendHelper $frontendHelper,
        Armourer $armourer,
        AddCustomHelmBody $addCustomHelmBody
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
        $this->addCustomHelmBody = $addCustomHelmBody;
    }

    public function getValue(): string
    {
        return <<<HTML
{$this->getAddHelm()}
{$this->getCurrentCustomHelms()}
<div class="row {$this->getVisibilityClass()}" id="chooseHelm">
  <div class="col">
    <div class="messages">
        {$this->getMessagesAboutHelms()}
    </div>
    <a title="Přidat vlastní helmu" href="{$this->getLinkToAddNewHelm()}" class="button add">+</a>
    <label>
      <select name="{$this->getHelmSelectName()}" title="Helma">
         {$this->getPossibleHelms()} 
      </select>
    </label>
  </div>
</div>
HTML;
    }

    private function getPossibleHelms(): string
    {
        $helms = [];
        foreach ($this->possibleArmaments->getPossibleHelms() as $possibleHelm) {
            /** @var HelmCode $helmCode */
            $helmCode = $possibleHelm['code'];
            $helms[] = <<<HTML
<option value="{$helmCode->getValue()}" {$this->getHelmSelected($helmCode)} {$this->getDisabled($possibleHelm['canUseIt'])}>
  {$this->getUsabilityPictogram($possibleHelm['canUseIt'])}{$helmCode->translateTo('cs')} {$this->getHelmProtection($helmCode)}
</option>
HTML;
        }

        return \implode("\n", $helms);
    }

    private function getHelmProtection(HelmCode $helmCode): string
    {
        return $this->frontendHelper->formatInteger($this->armourer->getProtectionOfHelm($helmCode));
    }

    private function getHelmSelected(HelmCode $helmCode): string
    {
        return $this->getSelected($this->currentArmaments->getCurrentHelm()->getValue(), $helmCode->getValue());
    }

    private function getHelmSelectName(): string
    {
        return AttackRequest::HELM;
    }

    private function getLinkToAddNewHelm(): string
    {
        return $this->frontendHelper->getLocalUrlToAction(AttackRequest::ADD_NEW_HELM);
    }

    private function getVisibilityClass(): string
    {
        return $this->customArmamentsState->isAddingNewHelm()
            ? 'hidden'
            : '';
    }

    private function getMessagesAboutHelms(): string
    {
        $messagesAboutHelms = [];
        foreach ($this->armamentsUsabilityMessages->getMessagesAboutHelms() as $messageAboutHelm) {
            $messagesAboutHelms [] = <<<HTML
          <div class="info"><?= $messageAboutHelm ?></div>
HTML;
        }

        return \implode("\n", $messagesAboutHelms);
    }

    private function getAddHelm(): string
    {
        if (!$this->customArmamentsState->isAddingNewHelm()) {
            return '';
        }

        return <<<HTML
<div id="addHelm" class="row add">
  {$this->addCustomHelmBody->getValue()}
</div>
HTML;
    }

    private function getCurrentCustomHelms(): string
    {
        $possibleCustomHelms = [];
        foreach ($this->currentArmamentsValues->getCurrentCustomHelmsValues() as $armorName => $armorValues) {
            /** @var array|string[] $armorValues */
            foreach ($armorValues as $typeName => $armorValue) {
                $possibleCustomHelms [] = <<<HTML
<input type="hidden" name="{$typeName}[{$armorName}]" value="<{$armorValue}">
HTML;
            }
        }

        return \implode("\n", $possibleCustomHelms);
    }
}