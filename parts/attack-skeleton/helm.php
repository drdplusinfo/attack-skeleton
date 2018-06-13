<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
if ($controller->isAddingNewHelm()) { ?>
  <div id="addHelm" class="row add">
      <?= $controller->getAddCustomHelmContent() ?>
  </div>
<?php }
foreach ($controller->getCurrentValues()->getCustomHelmsValues() as $helmName => $helmValues) {
    /** @var array|string[] $helmValues */
    foreach ($helmValues as $typeName => $helmValue) { ?>
      <input type="hidden" name="<?= $typeName ?>[<?= $helmName ?>]" value="<?= $helmValue ?>">
    <?php }
} ?>
<div class="row <?php if ($controller->isAddingNewHelm()) { ?>hidden<?php } ?>"
     id="chooseHelm">
  <div class="col">
    <div class="messages">
        <?php foreach ($controller->getMessagesAboutHelms() as $messageAboutHelm) { ?>
          <div class="info"><?= $messageAboutHelm ?></div>
        <?php } ?>
    </div>
    <a title="Přidat vlastní helmu"
       href="<?= $controller->getLocalUrlWithQuery([AttackController::ACTION => AttackController::ADD_NEW_HELM]) ?>"
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
</div>