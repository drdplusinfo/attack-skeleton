<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
if ($controller->isAddingNewBodyArmor()) { ?>
  <div id="addBodyArmor" class="row add">
      <?= $controller->getAddCustomBodyArmorContent() ?>
  </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomBodyArmorsValues() as $armorName => $armorValues) {
    /** @var array|string[] $armorValues */
    foreach ($armorValues as $typeName => $armorValue) { ?>
      <input type="hidden" name="<?= $typeName ?>[<?= $armorName ?>]" value="<?= $armorValue ?>">
    <?php }
} ?>
<div class="row <?php if ($controller->isAddingNewBodyArmor()) { ?>hidden<?php } ?>"
     id="chooseBodyArmor">
  <div class="col">
    <div class="messages">
        <?php foreach ($controller->getMessagesAboutArmors() as $messageAboutArmor) { ?>
          <div class="info"><?= $messageAboutArmor ?></div>
        <?php } ?>
    </div>
    <a title="Přidat vlastní zbroj"
       href="<?= $controller->getLocalUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_BODY_ARMOR]) ?>"
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
</div>