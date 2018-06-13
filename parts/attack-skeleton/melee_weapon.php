<?php
namespace DrdPlus\AttackSkeleton;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\ItemHoldingCode;

/** @var AttackController $controller */
$selectedMeleeWeapon = $controller->getAttack()->getCurrentMeleeWeapon();
$selectedMeleeWeaponValue = $selectedMeleeWeapon ? $selectedMeleeWeapon->getValue() : null;
if ($controller->isAddingNewMeleeWeapon()) { ?>
  <div id="addMeleeWeapon" class="row add">
      <?= $controller->getAddCustomMeleeWeaponContent() ?>
  </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomMeleeWeaponsValues() as $weaponName => $weaponValues) {
    /** @var array|string[] $weaponValues */
    foreach ($weaponValues as $typeName => $weaponValue) { ?>
      <input type="hidden" name="<?= $typeName ?>[<?= $weaponName ?>]" value="<?= $weaponValue ?>">
    <?php }
} ?>
<div class="<?php if ($controller->isAddingNewMeleeWeapon()) { ?>hidden<?php } ?>">
  <div class="row messages">
      <?php foreach ($controller->getMessagesAboutMeleeWeapons() as $messageAboutMeleeWeapon) { ?>
        <div class="info"><?= $messageAboutMeleeWeapon ?></div>
      <?php } ?>
  </div>
  <div class="row" id="chooseMeleeWeapon">
    <div class="col">
      <a title="Přidat vlastní zbraň na blízko"
         href="<?= $controller->getLocalUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_MELEE_WEAPON]) ?>"
         class="button add">+</a>
      <label>
        <select name="<?= AttackController::MELEE_WEAPON ?>" title="Melee weapon">
            <?php /** @var array $meleeWeaponsFromCategory */
            foreach ($controller->getMeleeWeapons() as $weaponCategory => $meleeWeaponsFromCategory) {
                ?>
              <optgroup label="<?= WeaponCategoryCode::getIt($weaponCategory)->translateTo('cs', 2) ?>">
                  <?php /** @var array $meleeWeapon */
                  foreach ($meleeWeaponsFromCategory as $meleeWeapon) {
                      /** @var MeleeWeaponCode $meleeWeaponCode */
                      $meleeWeaponCode = $meleeWeapon['code'];
                      ?>
                    <option value="<?= $meleeWeaponCode->getValue() ?>"
                            <?php if ($selectedMeleeWeaponValue === $meleeWeaponCode->getValue()) { ?>selected<?php }
                            if (!$meleeWeapon['canUseIt']) { ?>disabled<?php } ?>>
                        <?= (!$meleeWeapon['canUseIt'] ? '💪 ' : '') . $meleeWeaponCode->translateTo('cs') ?>
                    </option>
                  <?php } ?>
              </optgroup>
            <?php } ?>
        </select>
      </label>
    </div>
    <div class="col">
      <label>
        <input type="radio" value="<?= ItemHoldingCode::MAIN_HAND ?>"
               name="<?= AttackController::MELEE_WEAPON_HOLDING ?>"
               <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::MAIN_HAND) { ?>checked<?php } ?>>
        v dominantní ruce</label>
    </div>
    <div class="col">
      <label>
        <input type="radio" value="<?= ItemHoldingCode::OFFHAND ?>" name="<?= AttackController::MELEE_WEAPON_HOLDING ?>"
               <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::OFFHAND) { ?>checked<?php } ?>>
        v druhé
        ruce</label>
    </div>
    <div class="col">
      <label>
        <input type="radio" value="<?= ItemHoldingCode::TWO_HANDS ?>"
               name="<?= AttackController::MELEE_WEAPON_HOLDING ?>"
               <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::TWO_HANDS) { ?>checked<?php } ?>>
        obouručně
      </label>
    </div>
  </div>
</div>