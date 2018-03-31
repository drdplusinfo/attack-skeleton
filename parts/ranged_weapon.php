<?php
namespace DrdPlus\Calculators\AttackSkeleton;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Tables\Tables;

/** @var Controller $controller */
/** @var Tables $tables */
$selectedRangedWeapon = $controller->getFight()->getSelectedRangedWeapon();
$selectedRangedWeaponValue = $selectedRangedWeapon ? $selectedRangedWeapon->getValue() : null;
if ($controller->addingNewRangedWeapon()) { ?>
    <div id="addRangedWeapon" class="block add">
        <?php include __DIR__ . '/add_custom_ranged_weapon.php' ?>
    </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomRangedWeaponsValues() as $weaponName => $weaponValues) {
    /** @var array|string[] $weaponValues */
    foreach ($weaponValues as $typeName => $weaponValue) { ?>
        <input type="hidden" name="<?= $typeName ?>[<?= $weaponName ?>]" value="<?= $weaponValue ?>">
    <?php }
} ?>

<div class="block <?php if ($controller->addingNewRangedWeapon()) { ?>hidden<?php } ?>" id="chooseRangedWeapon">
    <div class="panel">
        <a title="Přidat vlastní zbraň na dálku"
           href="<?= $controller->getCurrentUrlWithQuery([Controller::ACTION => Controller::ADD_NEW_RANGED_WEAPON]) ?>"
           class="button add">+</a>
        <label>
            <select name="<?= Controller::RANGED_WEAPON ?>" title="Ranged weapon">
                <?php /** @var string[] $rangedWeaponsFromCategory */
                foreach ($controller->getRangedWeapons() as $weaponCategory => $rangedWeaponsFromCategory) {
                    ?>
                    <optgroup label="<?= WeaponCategoryCode::getIt($weaponCategory)->translateTo('cs', 2) ?>">
                        <?php /** @var array $rangedWeapon */
                        foreach ($rangedWeaponsFromCategory as $rangedWeapon) {
                            /** @var RangedWeaponCode $rangedWeaponCode */
                            $rangedWeaponCode = $rangedWeapon['code']; ?>
                            <option value="<?= $rangedWeaponCode->getValue() ?>"
                                    <?php if ($selectedRangedWeaponValue && $selectedRangedWeaponValue === $rangedWeaponCode->getValue()) { ?>selected<?php }
                                    if (!$rangedWeapon['canUseIt']) { ?>disabled<?php } ?>>
                                <?= (!$rangedWeapon['canUseIt'] ? '💪 ' : '') . $rangedWeaponCode->translateTo('cs') ?>
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
                   name="<?= Controller::RANGED_WEAPON_HOLDING ?>"
                   <?php if ($controller->getFight()->getSelectedRangedWeaponHolding()->getValue() === ItemHoldingCode::MAIN_HAND) { ?>checked<?php } ?>>
            v dominantní ruce</label>
    </div>
    <div class="panel">
        <label>
            <input type="radio" value="<?= ItemHoldingCode::OFFHAND ?>" name="<?= Controller::RANGED_WEAPON_HOLDING ?>"
                   <?php if ($controller->getFight()->getSelectedRangedWeaponHolding()->getValue() === ItemHoldingCode::OFFHAND) { ?>checked<?php } ?>>
            v druhé ruce</label>
    </div>
    <div class="panel">
        <label>
            <input type="radio" value="<?= ItemHoldingCode::TWO_HANDS ?>"
                   name="<?= Controller::RANGED_WEAPON_HOLDING ?>"
                   <?php if ($controller->getFight()->getSelectedRangedWeaponHolding()->getValue() === ItemHoldingCode::TWO_HANDS) { ?>checked<?php } ?>>
            obouručně
        </label>
    </div>
    <div class="block info-messages">
        <?php foreach ($controller->getMessagesAboutRanged() as $messageAboutRanged) { ?>
            <div class="info-message"><?= $messageAboutRanged ?></div>
        <?php } ?>
    </div>
</div>
