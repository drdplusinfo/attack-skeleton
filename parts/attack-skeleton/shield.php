<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
if ($controller->isAddingNewShield()) { ?>
  <div id="addShield" class="block add">
      <?php include __DIR__ . '/add-custom/add_custom_shield.php' ?>
  </div>
<?php } ?>
<div class="row">
  <div class="panel">
    <a title="Přidat vlastní štít"
       href="<?= $controller->getCurrentUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_SHIELD]) ?>"
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
