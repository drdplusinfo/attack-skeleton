<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
if ($controller->isAddingNewBodyArmor()) { ?>
  <div id="addBodyArmor" class="block add">
      <?php include __DIR__ . '/add-custom/add_custom_body_armor.php' ?>
  </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomBodyArmorsValues() as $armorName => $armorValues) {
    /** @var array|string[] $armorValues */
    foreach ($armorValues as $typeName => $armorValue) { ?>
      <input type="hidden" name="<?= $typeName ?>[<?= $armorName ?>]" value="<?= $armorValue ?>">
    <?php }
} ?>
<div class="block <?php if ($controller->isAddingNewBodyArmor() || $controller->isAddingNewHelm()) { ?>hidden<?php } ?>"
     id="chooseBodyArmor">
  <div class="panel">
    <a title="Přidat vlastní zbroj"
       href="<?= $controller->getCurrentUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_BODY_ARMOR]) ?>"
       class="button add">+</a>
    <label>
      <select name="<?= AttackController::BODY_ARMOR ?>">
          <?php /** @var array $bodyArmor */
          foreach ($controller->getBodyArmors() as $bodyArmor) {
              $bodyArmorCode = $bodyArmor['code']; ?>
            <option value="<?= $bodyArmorCode->getValue() ?>"
                    <?php if ($controller->getAttack()->getCurrentBodyArmor()->getValue() === $bodyArmorCode->getValue()) { ?>selected<?php }
                    if (!$bodyArmor['canUseIt']) { ?>disabled<?php } ?>>
                <?= (!$bodyArmor['canUseIt'] ? '💪 ' : '') . $bodyArmorCode->translateTo('cs') . ($controller->getAttack()->getProtectionOfBodyArmor($bodyArmorCode) > 0 ? (' +' . $controller->getAttack()->getProtectionOfBodyArmor($bodyArmorCode)) : '') ?>
            </option>
          <?php } ?>
      </select>
    </label>
  </div>
  <div class="block messages">
      <?php foreach ($controller->getMessagesAboutArmors() as $messageAboutArmor) { ?>
        <div class="message info"><?= $messageAboutArmor ?></div>
      <?php } ?>
  </div>
</div>
<?php
if ($controller->isAddingNewHelm()) { ?>
  <div id="addHelm" class="block add">
      <?php include __DIR__ . '/add-custom/add_custom_helm.php' ?>
  </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomHelmsValues() as $helmName => $helmValues) {
    /** @var array|string[] $helmValues */
    foreach ($helmValues as $typeName => $helmValue) { ?>
      <input type="hidden" name="<?= $typeName ?>[<?= $helmName ?>]" value="<?= $helmValue ?>">
    <?php }
} ?>
<div class="block <?php if ($controller->isAddingNewBodyArmor() || $controller->isAddingNewHelm()) { ?>hidden<?php } ?>"
     id="chooseHelm">
  <div class="panel">
    <a title="Přidat vlastní helmu"
       href="<?= $controller->getCurrentUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_HELM]) ?>"
       class="button add">+</a>
    <label>
      <select name="<?= AttackController::HELM ?>">
          <?php /** @var array $helm */
          foreach ($controller->getHelms() as $helm) {
              $helmCode = $helm['code']; ?>
            <option value="<?= $helmCode->getValue() ?>"
                    <?php if ($controller->getAttack()->getCurrentHelm()->getValue() === $helmCode->getValue()) { ?>selected<?php }
                    if (!$helm['canUseIt']) { ?>disabled<?php } ?>>
                <?= (!$helm['canUseIt'] ? '💪 ' : '') . $helmCode->translateTo('cs') . ($controller->getAttack()->getProtectionOfHelm($helmCode) > 0 ? (' +' . $controller->getAttack()->getProtectionOfHelm($helmCode)) : '') ?>
            </option>
          <?php } ?>
      </select>
    </label>
  </div>
  <div class="block messages">
      <?php foreach ($controller->getMessagesAboutHelms() as $messageAboutHelm) { ?>
        <div class="info"><?= $messageAboutHelm ?></div>
      <?php } ?>
  </div>
</div>