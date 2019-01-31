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
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomMeleeWeaponBody;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\ItemHoldingCode;

class MeleeWeaponBody extends AbstractArmamentBody
{
    /** @var AddCustomMeleeWeaponBody */
    private $addCustomMeleeWeaponBody;

    public function __construct(
        CustomArmamentsState $customArmamentsState,
        CurrentArmaments $currentArmaments,
        CurrentArmamentsValues $currentArmamentsValues,
        PossibleArmaments $possibleArmaments,
        ArmamentsUsabilityMessages $armamentsUsabilityMessages,
        FrontendHelper $frontendHelper,
        Armourer $armourer,
        AddCustomMeleeWeaponBody $addCustomMeleeWeaponBody
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
        $this->addCustomMeleeWeaponBody = $addCustomMeleeWeaponBody;
    }

    private function getAddCustomMeleeWeapon(): string
    {
        if (!$this->customArmamentsState->isAddingNewMeleeWeapon()) {
            return '';
        }

        return <<<HTML
<div id="addMeleeWeapon" class="row add">
  {$this->addCustomMeleeWeaponBody->getValue()}
</div>
HTML;
    }

    private function getCurrentCustomMeleeWeapons(): string
    {
        $currentCustomMeleeWeapons = '';
        foreach ($this->currentArmamentsValues->getCurrentCustomMeleeWeaponsValues() as $weaponName => $weaponValues) {
            /** @var array|string[] $weaponValues */
            foreach ($weaponValues as $typeName => $weaponValue) {
                $currentCustomMeleeWeapons .= <<<HTML
<input type="hidden" name="{$typeName}[{$weaponName}]" value="{$weaponValue}">
HTML;
            }
        }

        return $currentCustomMeleeWeapons;
    }

    private function getVisibilityClass(): string
    {
        return $this->customArmamentsState->isAddingNewMeleeWeapon()
            ? 'hidden'
            : '';
    }

    private function getMessagesAboutMeleeWeapons(): string
    {
        $messagesAboutMeleeWeapons = '';
        foreach ($this->armamentsUsabilityMessages->getMessagesAboutMeleeWeapons() as $messageAboutMeleeWeapon) {
            $messagesAboutMeleeWeapons .= <<<HTML
<div class="info">{$messageAboutMeleeWeapon}</div>
HTML;
        }

        return $messagesAboutMeleeWeapons;
    }

    private function getUrlToAddNewMeleeWeapon(): string
    {
        return $this->frontendHelper->getLocalUrlToAction(FrontendHelper::ADD_NEW_MELEE_WEAPON);
    }

    private function getMeleeWeaponSelectName(): string
    {
        return FrontendHelper::MELEE_WEAPON;
    }

    private function getTranslatedWeaponCategory(string $weaponCategory): string
    {
        return WeaponCategoryCode::getIt($weaponCategory)->translateTo('cs', 2);
    }

    private function getPossibleMeleeWeaponsOfCategory(array $meleeWeaponsFromCategory): string
    {
        $possibleMeleeWeaponsOfCategory = '';
        /** @var array $meleeWeapon */
        foreach ($meleeWeaponsFromCategory as $meleeWeapon) {
            /** @var MeleeWeaponCode $meleeWeaponCode */
            $meleeWeaponCode = $meleeWeapon['code'];
            $possibleMeleeWeaponsOfCategory .= <<<HTML
<option value="{$meleeWeaponCode->getValue()}" {$this->getSelected($meleeWeaponCode)} {$this->getDisabled($meleeWeapon['canUseIt'])}>
  {$this->getUsabilityPictogram($meleeWeapon['canUseIt'])}{$meleeWeaponCode->translateTo('cs')}
</option>
HTML;
        }

        return $possibleMeleeWeaponsOfCategory;
    }

    private function getSelected(MeleeWeaponCode $meleeWeaponCode): string
    {
        return $this->currentArmaments->getCurrentMeleeWeapon()->getValue() === $meleeWeaponCode->getValue()
            ? 'selected'
            : '';
    }

    private function getPossibleMeleeWeapons(): string
    {
        $possibleMeleeWeapons = '';
        /** @var array $meleeWeaponsFromCategory */
        foreach ($this->possibleArmaments->getPossibleMeleeWeapons() as $weaponCategory => $meleeWeaponsFromCategory) {
            $possibleMeleeWeapons .= <<<HTML
<optgroup label="{$this->getTranslatedWeaponCategory($weaponCategory)}">
    {$this->getPossibleMeleeWeaponsOfCategory($meleeWeaponsFromCategory)}
</optgroup>
HTML;
        }

        return $possibleMeleeWeapons;
    }

    private function getMainHandHolding(): string
    {
        return ItemHoldingCode::MAIN_HAND;
    }

    private function getMeleeWeaponHoldingName(): string
    {
        return FrontendHelper::MELEE_WEAPON_HOLDING;
    }

    private function getCheckedMainHandHolding(): string
    {
        return $this->getCheckedHolding(ItemHoldingCode::MAIN_HAND);
    }

    private function getCheckedHolding(string $holdingToCheck): string
    {
        return $this->currentArmaments->getCurrentMeleeWeaponHolding()->getValue() === $holdingToCheck
            ? 'checked'
            : '';
    }

    private function getCheckedOffhandHolding(): string
    {
        return $this->getCheckedHolding(ItemHoldingCode::OFFHAND);
    }

    private function getCheckedTwoHandsHolding(): string
    {
        return $this->getCheckedHolding(ItemHoldingCode::TWO_HANDS);
    }

    private function getOffhandHolding(): string
    {
        return ItemHoldingCode::OFFHAND;
    }

    private function getTwoHandsHolding(): string
    {
        return ItemHoldingCode::TWO_HANDS;
    }

    public function getValue(): string
    {
        return <<<HTML
{$this->getAddCustomMeleeWeapon()}
{$this->getCurrentCustomMeleeWeapons()}
<div class="{$this->getVisibilityClass()}">
    <div class="row messages">
      {$this->getMessagesAboutMeleeWeapons()}
    </div>
    <div class="row" id="chooseMeleeWeapon">
        <div class="col">
            <a title="Přidat vlastní zbraň na blízko" href="{$this->getUrlToAddNewMeleeWeapon()}" class="button add">+</a>
            <label>
                <select name="{$this->getMeleeWeaponSelectName()}" title="Zbraň na blízko">
                    {$this->getPossibleMeleeWeapons()}
                </select>
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="{$this->getMainHandHolding()}" name="{$this->getMeleeWeaponHoldingName()}" {$this->getCheckedMainHandHolding()}>
                v dominantní ruce
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="{$this->getOffhandHolding()}" name="{$this->getMeleeWeaponHoldingName()}" {$this->getCheckedOffhandHolding()}>
                v druhé ruce
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="{$this->getTwoHandsHolding()}"
                       name="{$this->getMeleeWeaponHoldingName()}" {$this->getCheckedTwoHandsHolding()}>
                obouručně
            </label>
        </div>
    </div>
</div>
HTML;
    }
}