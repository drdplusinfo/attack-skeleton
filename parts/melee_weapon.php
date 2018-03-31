<?php
namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\ItemHoldingCode;

/** @var Controller $controller */
$selectedMeleeWeapon = $controller->getAttack()->getCurrentMeleeWeapon();
$selectedMeleeWeaponValue = $selectedMeleeWeapon ? $selectedMeleeWeapon->getValue() : null;
if ($controller->addingNewMeleeWeapon()) { ?>
    <div id="addMeleeWeapon" class="block add">
        <?php include __DIR__ . '/add_custom_melee_weapon.php' ?>
    </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomMeleeWeaponsValues() as $weaponName => $weaponValues) {
    /** @var array|string[] $weaponValues */
    foreach ($weaponValues as $typeName => $weaponValue) { ?>
        <input type="hidden" name="<?= $typeName ?>[<?= $weaponName ?>]" value="<?= $weaponValue ?>">
    <?php }
} ?>
<div class="block <?php if ($controller->addingNewMeleeWeapon()) { ?>hidden<?php } ?>" id="chooseMeleeWeapon">
    <div class="panel">
        <a title="Přidat vlastní zbraň na blízko"
           href="<?= $controller->getCurrentUrlWithQuery([Controller::ACTION => Controller::ADD_NEW_MELEE_WEAPON]) ?>"
           class="button add">+</a>
        <label>
            <select name="<?= Controller::MELEE_WEAPON ?>" title="Melee weapon">
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
    <div class="panel">
        <label>
            <input type="radio" value="<?= ItemHoldingCode::MAIN_HAND ?>"
                   name="<?= Controller::MELEE_WEAPON_HOLDING ?>"
                   <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::MAIN_HAND) { ?>checked<?php } ?>>
            v dominantní ruce</label>
    </div>
    <div class="panel">
        <label>
            <input type="radio" value="<?= ItemHoldingCode::OFFHAND ?>" name="<?= Controller::MELEE_WEAPON_HOLDING ?>"
                   <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::OFFHAND) { ?>checked<?php } ?>>
            v druhé
            ruce</label>
    </div>
    <div class="panel">
        <label>
            <input type="radio" value="<?= ItemHoldingCode::TWO_HANDS ?>"
                   name="<?= Controller::MELEE_WEAPON_HOLDING ?>"
                   <?php if ($controller->getAttack()->getCurrentMeleeWeaponHolding()->getValue() === ItemHoldingCode::TWO_HANDS) { ?>checked<?php } ?>>
            obouručně
        </label>
    </div>
    <div class="block info-messages">
        <?php foreach ($controller->getMessagesAboutMelee() as $messageAboutMelee) { ?>
            <div class="info-message"><?= $messageAboutMelee ?></div>
        <?php } ?>
    </div>
</div>