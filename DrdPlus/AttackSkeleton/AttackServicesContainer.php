<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton;

use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomHelmBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomMeleeWeaponBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomBodyArmorBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomRangedWeaponBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomShieldBody;
use DrdPlus\AttackSkeleton\Web\BodyArmorBody;
use DrdPlus\AttackSkeleton\Web\BodyPropertiesBody;
use DrdPlus\AttackSkeleton\Web\HelmBody;
use DrdPlus\AttackSkeleton\Web\MeleeWeaponBody;
use DrdPlus\AttackSkeleton\Web\RangedWeaponBody;
use DrdPlus\AttackSkeleton\Web\ShieldBody;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\Tables\Tables;

class AttackServicesContainer extends CalculatorServicesContainer
{
    /** @var BodyPropertiesBody */
    private $bodyPropertiesBody;
    /** @var CurrentProperties */
    private $currentProperties;
    /** @var PossibleArmaments */
    private $possibleArmaments;
    /** @var Armourer */
    private $armourer;
    /** @var Tables */
    private $tables;
    /** @var CurrentArmaments */
    private $currentArmaments;
    /** @var CustomArmamentsRegistrar */
    private $customArmamentsRegistrar;
    /** @var CustomArmamentsService */
    private $customArmamentsService;
    /** @var CurrentArmamentsValues */
    private $currentArmamentsValues;
    /** @var ArmamentsUsabilityMessages */
    private $armamentsUsabilityMessages;
    /** @var FrontendHelper */
    private $frontendHelper;
    /** @var CustomArmamentsState */
    private $customArmamentsState;
    /** @var BodyArmorBody */
    private $bodyArmorBody;
    /** @var AddCustomBodyArmorBody */
    private $addCustomBodyArmorBody;
    /** @var HelmBody */
    private $helmBody;
    /** @var AddCustomHelmBody */
    private $addCustomHelmBody;
    /** @var MeleeWeaponBody */
    private $meleeWeaponBody;
    /** @var AddCustomMeleeWeaponBody */
    private $addCustomMeleeWeaponBody;
    /** @var RangedWeaponBody */
    private $rangedWeaponBody;
    /** @var AddCustomRangedWeaponBody */
    private $addCustomRangedWeaponBody;
    /** @var ShieldBody */
    private $shieldBody;
    /** @var AddCustomShieldBody */
    private $addCustomShieldBody;

    public function getRulesMainBodyParameters(): array
    {
        return [
            'historyDeletion' => $this->getHistoryDeletionBody(),
            'calculatorDebugContacts' => $this->getCalculatorDebugContactsBody(),
            'bodyProperties' => $this->getBodyPropertiesBody(),
            'bodyArmor' => $this->getBodyArmorBody(),
            'helm' => $this->getHelmBody(),
        ];
    }

    public function getBodyPropertiesBody(): BodyPropertiesBody
    {
        if ($this->bodyPropertiesBody === null) {
            $this->bodyPropertiesBody = new BodyPropertiesBody($this->getCurrentProperties());
        }

        return $this->bodyPropertiesBody;
    }

    public function getBodyArmorBody(): BodyArmorBody
    {
        if ($this->bodyArmorBody === null) {
            $this->bodyArmorBody = new BodyArmorBody(
                $this->getCustomArmamentsState(),
                $this->getCurrentArmaments(),
                $this->getCurrentArmamentsValues(),
                $this->getPossibleArmaments(),
                $this->getArmamentsUsabilityMessages(),
                $this->getFrontendHelper(),
                $this->getArmourer(),
                $this->getAddCustomBodyArmorBody()
            );
        }

        return $this->bodyArmorBody;
    }

    public function getAddCustomBodyArmorBody(): AddCustomBodyArmorBody
    {
        if ($this->addCustomBodyArmorBody === null) {
            $this->addCustomBodyArmorBody = new AddCustomBodyArmorBody($this->getFrontendHelper());
        }

        return $this->addCustomBodyArmorBody;
    }

    public function getHelmBody(): HelmBody
    {
        if ($this->helmBody === null) {
            $this->helmBody = new HelmBody(
                $this->getCustomArmamentsState(),
                $this->getCurrentArmaments(),
                $this->getCurrentArmamentsValues(),
                $this->getPossibleArmaments(),
                $this->getArmamentsUsabilityMessages(),
                $this->getFrontendHelper(),
                $this->getArmourer(),
                $this->getAddCustomHelmBody()
            );
        }

        return $this->helmBody;
    }

    public function getAddCustomHelmBody(): AddCustomHelmBody
    {
        if ($this->addCustomHelmBody === null) {
            $this->addCustomHelmBody = new AddCustomHelmBody($this->getFrontendHelper());
        }

        return $this->addCustomHelmBody;
    }

    public function getMeleeWeaponBody(): MeleeWeaponBody
    {
        if ($this->meleeWeaponBody === null) {
            $this->meleeWeaponBody = new MeleeWeaponBody(
                $this->getCustomArmamentsState(),
                $this->getCurrentArmaments(),
                $this->getCurrentArmamentsValues(),
                $this->getPossibleArmaments(),
                $this->getArmamentsUsabilityMessages(),
                $this->getFrontendHelper(),
                $this->getArmourer(),
                $this->getAddCustomMeleeWeaponBody()
            );
        }

        return $this->meleeWeaponBody;
    }

    public function getAddCustomMeleeWeaponBody(): AddCustomMeleeWeaponBody
    {
        if ($this->addCustomMeleeWeaponBody === null) {
            $this->addCustomMeleeWeaponBody = new AddCustomMeleeWeaponBody($this->getFrontendHelper());
        }

        return $this->addCustomMeleeWeaponBody;
    }

    public function getRangedWeaponBody(): RangedWeaponBody
    {
        if ($this->rangedWeaponBody === null) {
            $this->rangedWeaponBody = new RangedWeaponBody(
                $this->getCustomArmamentsState(),
                $this->getCurrentArmaments(),
                $this->getCurrentArmamentsValues(),
                $this->getPossibleArmaments(),
                $this->getArmamentsUsabilityMessages(),
                $this->getFrontendHelper(),
                $this->getArmourer(),
                $this->getAddCustomRangedWeaponBody()
            );
        }

        return $this->rangedWeaponBody;
    }

    public function getAddCustomRangedWeaponBody(): AddCustomRangedWeaponBody
    {
        if ($this->addCustomRangedWeaponBody === null) {
            $this->addCustomRangedWeaponBody = new AddCustomRangedWeaponBody($this->getFrontendHelper());
        }

        return $this->addCustomRangedWeaponBody;
    }

    public function getShieldBody(): ShieldBody
    {
        if ($this->shieldBody === null) {
            $this->shieldBody = new ShieldBody(
                $this->getCustomArmamentsState(),
                $this->getCurrentArmaments(),
                $this->getCurrentArmamentsValues(),
                $this->getPossibleArmaments(),
                $this->getArmamentsUsabilityMessages(),
                $this->getFrontendHelper(),
                $this->getArmourer(),
                $this->getAddCustomShieldBody()
            );
        }

        return $this->shieldBody;
    }

    public function getAddCustomShieldBody(): AddCustomShieldBody
    {
        if ($this->addCustomShieldBody === null) {
            $this->addCustomShieldBody = new AddCustomShieldBody($this->getFrontendHelper());
        }

        return $this->addCustomShieldBody;
    }

    public function getCurrentProperties(): CurrentProperties
    {
        if ($this->currentProperties === null) {
            $this->currentProperties = new CurrentProperties($this->getCurrentValues());
        }

        return $this->currentProperties;
    }

    public function getPossibleArmaments(): PossibleArmaments
    {
        if ($this->possibleArmaments === null) {
            $this->possibleArmaments = new PossibleArmaments(
                $this->getArmourer(),
                $this->getCurrentProperties(),
                $this->getCurrentArmaments()->getCurrentMeleeWeaponHolding(),
                $this->getCurrentArmaments()->getCurrentRangedWeaponHolding()
            );
        }

        return $this->possibleArmaments;
    }

    public function getArmourer(): Armourer
    {
        if ($this->armourer === null) {
            $this->armourer = new Armourer($this->getTables());
        }

        return $this->getArmourer();
    }

    public function getCurrentArmaments(): CurrentArmaments
    {
        if ($this->currentArmaments === null) {
            $this->currentArmaments = new CurrentArmaments(
                $this->getCurrentProperties(),
                $this->getCurrentArmamentsValues(),
                $this->getArmourer(),
                $this->getCustomArmamentsRegistrar()
            );
        }

        return $this->currentArmaments;
    }

    public function getCustomArmamentsRegistrar(): CustomArmamentsRegistrar
    {
        if ($this->customArmamentsRegistrar === null) {
            $this->customArmamentsRegistrar = new CustomArmamentsRegistrar(
                $this->getCustomArmamentsService(),
                $this->getTables()
            );
        }

        return $this->customArmamentsRegistrar;
    }

    public function getTables(): Tables
    {
        if ($this->tables === null) {
            $this->tables = Tables::getIt();
        }

        return $this->tables;
    }

    public function getFrontendHelper(): FrontendHelper
    {
        if ($this->frontendHelper === null) {
            $this->frontendHelper = new FrontendHelper($this->getCurrentValues());
        }

        return $this->frontendHelper;
    }

    public function getCustomArmamentsService(): CustomArmamentsService
    {
        if ($this->customArmamentsService === null) {
            $this->customArmamentsService = new CustomArmamentsService($this->getArmourer());
        }

        return $this->customArmamentsService;
    }

    public function getCurrentArmamentsValues(): CurrentArmamentsValues
    {
        if ($this->currentArmamentsValues === null) {
            $this->currentArmamentsValues = new CurrentArmamentsValues($this->getCurrentValues());
        }

        return $this->currentArmamentsValues;
    }

    public function getArmamentsUsabilityMessages(): ArmamentsUsabilityMessages
    {
        if ($this->armamentsUsabilityMessages === null) {
            $this->armamentsUsabilityMessages = new ArmamentsUsabilityMessages($this->getPossibleArmaments());
        }

        return $this->armamentsUsabilityMessages;
    }

    public function getCustomArmamentsState(): CustomArmamentsState
    {
        if ($this->customArmamentsState === null) {
            $this->customArmamentsState = new CustomArmamentsState($this->getCurrentValues());
        }

        return $this->customArmamentsState;
    }
}