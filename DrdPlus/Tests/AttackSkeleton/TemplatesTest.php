<?php
declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\CustomArmamentsState;
use DrdPlus\AttackSkeleton\FrontendHelper;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomBodyArmorBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomHelmBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomMeleeWeaponBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomRangedWeaponBody;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomShieldBody;
use DrdPlus\AttackSkeleton\Web\BodyArmorBody;
use DrdPlus\AttackSkeleton\Web\BodyPropertiesBody;
use DrdPlus\AttackSkeleton\Web\HelmBody;
use DrdPlus\AttackSkeleton\Web\MeleeWeaponBody;
use DrdPlus\AttackSkeleton\Web\RangedWeaponBody;
use DrdPlus\AttackSkeleton\Web\ShieldBody;
use Granam\WebContentBuilder\Web\BodyInterface;

class TemplatesTest extends AbstractAttackTest
{
    /**
     * @test
     */
    public function I_can_use_template_to_add_custom_melee_weapon(): void
    {
        $this->I_can_use_template_to_add_custom_armament(new AddCustomMeleeWeaponBody(new FrontendHelper()));
    }

    private function I_can_use_template_to_add_custom_armament(BodyInterface $body): void
    {
        self::assertNotSame('', $body->getValue());
    }

    /**
     * @test
     */
    public function I_can_use_template_to_add_custom_ranged_weapon(): void
    {
        $this->I_can_use_template_to_add_custom_armament(new AddCustomRangedWeaponBody(new FrontendHelper()));
    }

    /**
     * @test
     */
    public function I_can_use_template_to_add_custom_body_armor(): void
    {
        $this->I_can_use_template_to_add_custom_armament(new AddCustomBodyArmorBody(new FrontendHelper()));
    }

    /**
     * @test
     */
    public function I_can_use_template_to_add_custom_helm(): void
    {
        $this->I_can_use_template_to_add_custom_armament(new AddCustomHelmBody(new FrontendHelper()));
    }

    /**
     * @test
     */
    public function I_can_use_template_to_add_custom_shield(): void
    {
        $this->I_can_use_template_to_add_custom_armament(new AddCustomShieldBody(
            new FrontendHelper()));
    }

    /**
     * @test
     */
    public function I_can_use_template_with_body_armors(): void
    {
        $bodyArmorBody = new BodyArmorBody(
            $this->createCustomArmamentsState(),
            $this->createDefaultCurrentArmaments(),
            $this->createEmptyCurrentArmamentValues(),
            $this->createAllPossibleArmaments(),
            $this->createEmptyArmamentsUsabilityMessages(),
            $frontendHelper = new FrontendHelper(),
            Armourer::getIt(),
            new AddCustomBodyArmorBody($frontendHelper)
        );
        self::assertSame(
            <<<HTML
<div class="row " id="chooseBodyArmor">
  <div class="col">
    <div class="messages">
      
    </div>
    <a title="Přidat vlastní zbroj" href="?action=add_new_body_armor" class="button add">+</a>
    <label>
      <select name="body_armor" title="Zbroj">
        <option value="without_armor" selected >
  beze zbroje +0
</option>
<option value="padded_armor"  >
  prošívaná zbroj +2
</option>
<option value="leather_armor"  >
  kožená zbroj +3
</option>
<option value="hobnailed_armor"  >
  pobíjená zbroj +4
</option>
<option value="chainmail_armor"  >
  kroužková zbroj +6
</option>
<option value="scale_armor"  >
  šupinová zbroj +7
</option>
<option value="plate_armor"  >
  plátová zbroj +9
</option>
<option value="full_plate_armor"  >
  plná plátová zbroj +10
</option>
      </select>
    </label>
  </div>
</div>
HTML
            ,
            \trim($bodyArmorBody->getValue())
        );
    }

    private function createCustomArmamentsState(): CustomArmamentsState
    {
        return new CustomArmamentsState($this->createEmptyCurrentValues());
    }

    /**
     * @test
     */
    public function I_can_use_template_with_helms(): void
    {
        $helmBody = new HelmBody(
            $this->createCustomArmamentsState(),
            $this->createDefaultCurrentArmaments(),
            $this->createEmptyCurrentArmamentValues(),
            $this->createAllPossibleArmaments(),
            $this->createEmptyArmamentsUsabilityMessages(),
            $frontendHelper = new FrontendHelper(),
            Armourer::getIt(),
            new AddCustomHelmBody($frontendHelper)
        );
        self::assertSame(
            <<<HTML
<div class="row " id="chooseHelm">
  <div class="col">
    <div class="messages">
        
    </div>
    <a title="Přidat vlastní helmu" href="?action=add_new_helm" class="button add">+</a>
    <label>
      <select name="helm" title="Helma">
         <option value="without_helm" selected >
  bez helmy +0
</option>
<option value="leather_cap"  >
  kožená čapka +1
</option>
<option value="chainmail_hood"  >
  kroužková kukla +2
</option>
<option value="conical_helm"  >
  konická helma +3
</option>
<option value="full_helm"  >
  plná přilba +4
</option>
<option value="barrel_helm"  >
  hrncová přilba +5
</option>
<option value="great_helm"  >
  kbelcová přilba +7
</option> 
      </select>
    </label>
  </div>
</div>
HTML
            ,
            \trim($helmBody->getValue())
        );
    }

    /**
     * @test
     */
    public function I_can_use_template_with_body_properties(): void
    {
        $bodyPropertiesBody = new BodyPropertiesBody($this->createMaximalCurrentProperties(40, 5, 220));
        self::assertSame(<<<HTML
<div class="row body-properties">
  <div class="col">
    <div><label for="strength">Síla</label></div>
    <div><input id="strength" type="number" name="strength" min="-40" max="40"
                value="40">
    </div>
  </div>
  <div class="col">
    <div><label for="agility">Obratnost</label></div>
    <div><input id="agility" type="number" name="agility" min="-40" max="40"
                value="40">
    </div>
  </div>
  <div class="col">
    <div><label for="knack">Zručnost</label></div>
    <div><input id="knack" type="number" name="knack" min="-40" max="40"
                value="40">
    </div>
  </div>
  <div class="col">
    <div><label for="will">Vůle</label></div>
    <div><input id="will" type="number" name="will" min="-40" max="40"
                value="40">
    </div>
  </div>
  <div class="col">
    <div><label for="intelligence">Inteligence</label></div>
    <div>
      <input id="intelligence" type="number" name="intelligence" min="-40" max="40"
             value="40">
    </div>
  </div>
  <div class="col">
    <div><label for="charisma">Charisma</label></div>
    <div>
      <input id="charisma" type="number" name="charisma" min="-40" max="40"
             value="40"></div>
  </div>
  <div class="col">
    <div><label for="height">Výška v cm</label></div>
    <div>
      <input id="height" type="number" name="height_in_cm" min="110"
             max="290"
             value="220">
    </div>
  </div>
  <div class="col">
    <div><label for="size">Velikost</label></div>
    <div><input id="size" type="number" name="size" min="-10" max="10"
                value="5">
    </div>
  </div>
</div>
HTML
            ,
            \trim($bodyPropertiesBody->getValue())
        );
    }

    /**
     * @test
     */
    public function I_can_use_template_with_melee_weapons(): void
    {
        $meleeWeaponBody = new MeleeWeaponBody(
            $this->createCustomArmamentsState(),
            $this->createDefaultCurrentArmaments(),
            $this->createEmptyCurrentArmamentValues(),
            $this->createAllPossibleArmaments(),
            $this->createEmptyArmamentsUsabilityMessages(),
            $frontendHelper = new FrontendHelper(),
            Armourer::getIt(),
            new AddCustomMeleeWeaponBody($frontendHelper)
        );
        self::assertSame(<<<HTML
<div class="">
    <div class="row messages">
      
    </div>
    <div class="row" id="chooseMeleeWeapon">
        <div class="col">
            <a title="Přidat vlastní zbraň na blízko" href="?action=add_new_melee_weapon" class="button add">+</a>
            <label>
                <select name="melee_weapon" title="Zbraň na blízko">
                    <optgroup label="sekery">
    <option value="light_axe"  >
  lehká sekerka
</option><option value="axe"  >
  sekera
</option><option value="war_axe"  >
  válečná sekera
</option><option value="two_handed_axe"  >
  obouruční sekera
</option>
</optgroup><optgroup label="nože a dýky">
    <option value="knife"  >
  nůž
</option><option value="dagger"  >
  dýka
</option><option value="stabbing_dagger"  >
  bodná dýka
</option><option value="long_knife"  >
  dlouhý nůž
</option><option value="long_dagger"  >
  dlouhá dýka
</option>
</optgroup><optgroup label="palice a kyje">
    <option value="cudgel"  >
  obušek
</option><option value="club"  >
  kyj
</option><option value="hobnailed_club"  >
  okovaný kyj
</option><option value="light_mace"  >
  lehký palcát
</option><option value="mace"  >
  palcát
</option><option value="heavy_club"  >
  těžký kyj
</option><option value="war_hammer"  >
  válečné kladivo
</option><option value="two_handed_club"  >
  obouruční kyj
</option><option value="heavy_sledgehammer"  >
  těžký perlík
</option>
</optgroup><optgroup label="řemdihy a bijáky">
    <option value="light_morgenstern"  >
  lehký biják
</option><option value="morgenstern"  >
  biják
</option><option value="heavy_morgenstern"  >
  těžký biják
</option><option value="flail"  >
  cep
</option><option value="morningstar"  >
  řemdih
</option><option value="hobnailed_flail"  >
  okovaný cep
</option><option value="heavy_morningstar"  >
  těžký řemdih
</option>
</optgroup><optgroup label="šavle a tesáky">
    <option value="machete"  >
  mačeta
</option><option value="light_saber"  >
  lehká šavle
</option><option value="bowie_knife"  >
  tesák
</option><option value="saber"  >
  šavle
</option><option value="heavy_saber"  >
  těžká šavle
</option>
</optgroup><optgroup label="hole a kopí">
    <option value="light_spear"  >
  lehké kopí
</option><option value="shortened_staff"  >
  zkrácená hůl
</option><option value="light_staff"  >
  lehká hůl
</option><option value="spear"  >
  kopí
</option><option value="hobnailed_staff"  >
  okovaná hůl
</option><option value="long_spear"  >
  dlouhé kopí
</option><option value="heavy_hobnailed_staff"  >
  těžká okovaná hůl
</option><option value="pike"  >
  píka
</option><option value="metal_staff"  >
  kovová hůl
</option>
</optgroup><optgroup label="meče">
    <option value="short_sword"  >
  krátký meč
</option><option value="hanger"  >
  krátký široký meč
</option><option value="glaive"  >
  široký meč
</option><option value="long_sword"  >
  dlouhý meč
</option><option value="one_and_half_handed_sword"  >
  jedenapůlruční meč
</option><option value="barbarian_sword"  >
  barbarský meč
</option><option value="two_handed_sword"  >
  obouruční meč
</option>
</optgroup><optgroup label="sudlice a trojzubce">
    <option value="pitchfork"  >
  vidle
</option><option value="light_voulge"  >
  lehká sudlice
</option><option value="light_trident"  >
  lehký trojzubec
</option><option value="halberd"  >
  halapartna
</option><option value="heavy_voulge"  >
  těžká sudlice
</option><option value="heavy_trident"  >
  těžký trojzubec
</option><option value="heavy_halberd"  >
  těžká halapartna
</option>
</optgroup><optgroup label="beze zbraně">
    <option value="hand" selected >
  ruka
</option><option value="hobnailed_glove"  >
  okovaná rukavice
</option><option value="leg"  >
  noha
</option><option value="hobnailed_boot"  >
  okovaná bota
</option>
</optgroup>
                </select>
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="main_hand" name="melee_weapon_holding" checked>
                v dominantní ruce
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="offhand" name="melee_weapon_holding" >
                v druhé ruce
            </label>
        </div>
        <div class="col">
            <label>
                <input type="radio" value="two_hands"
                       name="melee_weapon_holding" >
                obouručně
            </label>
        </div>
    </div>
</div>
HTML
            , \trim($meleeWeaponBody->getValue())
        );
    }

    /**
     * @test
     */
    public function I_can_use_template_with_ranged_weapons(): void
    {
        $rangedWeaponBody = new RangedWeaponBody(
            $this->createCustomArmamentsState(),
            $this->createDefaultCurrentArmaments(),
            $this->createEmptyCurrentArmamentValues(),
            $this->createAllPossibleArmaments(),
            $this->createEmptyArmamentsUsabilityMessages(),
            $frontendHelper = new FrontendHelper(),
            Armourer::getIt(),
            new AddCustomRangedWeaponBody($frontendHelper)
        );
        self::assertSame(<<<HTML
<div class="">
    <div class="row messages">
      
    </div>
    <div class="row" id="chooseRangedWeapon">
      <div class="col">
    <a title="Přidat vlastní zbraň na dálku" href="?action=add_new_ranged_weapon" class="button add">+</a>
    <label>
        <select name="ranged_weapon" title="Zbraň na dálku">
            <optgroup label="vrhací zbraně">
    <option value="sand" selected >
  písek
</option><option value="rock"  >
  kámen
</option><option value="throwing_dagger"  >
  vrhací dýka
</option><option value="light_throwing_axe"  >
  lehká vrhací sekera
</option><option value="war_throwing_axe"  >
  válečná vrhací sekera
</option><option value="throwing_hammer"  >
  vrhací kladivo
</option><option value="shuriken"  >
  hvězdice
</option><option value="spear"  >
  kopí
</option><option value="javelin"  >
  oštěp
</option><option value="sling"  >
  prak
</option>
</optgroup><optgroup label="luky">
    <option value="short_bow"  >
  krátký luk
</option><option value="long_bow"  >
  dlouhý luk
</option><option value="short_composite_bow"  >
  krátký skládaný luk
</option><option value="long_composite_bow"  >
  dlouhý skládaný luk
</option><option value="power_bow"  >
  silový luk
</option>
</optgroup><optgroup label="kuše">
    <option value="minicrossbow"  >
  minikuše
</option><option value="light_crossbow"  >
  lehká kuše
</option><option value="military_crossbow"  >
  válečná kuše
</option><option value="heavy_crossbow"  >
  těžká kuše
</option>
</optgroup>
        </select>
    </label>
</div>
      <div class="col">
    <label>
        <input type="radio" value="main_hand" name="ranged_weapon_holding" checked>
        v dominantní ruce
    </label>
</div>
<div class="col">
    <label>
        <input type="radio" value="offhand" name="ranged_weapon_holding" >
        v druhé ruce
    </label>
</div>
<div class="col">
    <label>
        <input type="radio" value="two_hands"
               name="ranged_weapon_holding" >
        obouručně
    </label>
</div>
    </div>
</div>
HTML
            , \trim($rangedWeaponBody->getValue())
        );
    }

    /**
     * @test
     */
    public function I_can_use_template_with_shields(): void
    {
        $shieldBody = new ShieldBody(
            $this->createCustomArmamentsState(),
            $this->createDefaultCurrentArmaments(),
            $this->createEmptyCurrentArmamentValues(),
            $this->createAllPossibleArmaments(),
            $this->createEmptyArmamentsUsabilityMessages(),
            $frontendHelper = new FrontendHelper(),
            Armourer::getIt(),
            new AddCustomShieldBody($frontendHelper)
        );
        self::assertSame(<<<HTML
<div class="row " id="chooseShield">
  <div class="col">
    <div class="messages">
        
    </div>
    <a title="Přidat vlastní štít" href="?action=add_new_shield" class="button add">+</a>
    <label>
      <select name="shield" title="Štít">
         <option value="without_shield" selected >
  bez štítu +0
</option><option value="buckler"  >
  pěstní štítek +2
</option><option value="small_shield"  >
  malý štít +4
</option><option value="medium_shield"  >
  střední štít +5
</option><option value="heavy_shield"  >
  velký štít +6
</option><option value="pavise"  >
  pavéza +7
</option> 
      </select>
    </label>
  </div>
</div>
HTML
            , \trim($shieldBody->getValue())
        );
    }
}