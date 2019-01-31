<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
if ($controller->isAddingNewShield()) { ?>
  <div id="addShield" class="row add">
      <?= $controller->getAddCustomShieldContent() ?>
  </div>
<?php } ?>
<div class="<?php if ($controller->isAddingNewShield()) { ?>hidden<?php } ?>">
  <div class="row messages">
      <?php foreach ($controller->getMessagesAboutShields() as $messageAboutShield) { ?>
        <div class="info"><?= $messageAboutShield ?></div>
      <?php } ?>
  </div>
  <div class="row">
    <div class="col">
      <a title="Přidat vlastní štít"
         href="<?= $controller->getLocalUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_SHIELD]) ?>"
         class="button add">+</a>
      <label>
        <select name="<?= AttackController::SHIELD ?>"><?php
            /** @var array $shield */
            foreach ($controller->getShields() as $shield) {
                $shieldCode = $shield['code']; ?>
              <option value="<?= $shieldCode->getValue() ?>"
                      <?php if ($controller->getAttack()->getCurrentShield()->getValue() === $shieldCode->getValue()) { ?>selected<?php }
                      if (!$shield['canUseIt']) { ?>disabled<?php } ?>>
                  <?= (!$shield['canUseIt'] ? '💪 ' : '') . $shieldCode->translateTo('cs') . ($controller->getAttack()->getCoverOfShield($shieldCode) > 0 ? (' +' . $controller->getAttack()->getCoverOfShield($shieldCode)) : '') ?>
              </option>
            <?php } ?>
        </select>
      </label>
    </div>
  </div>
</div>
