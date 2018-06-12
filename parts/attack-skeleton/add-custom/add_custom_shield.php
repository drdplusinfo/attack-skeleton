<?php
namespace DrdPlus\AttackSkeleton;

/** @var \DrdPlus\AttackSkeleton\AttackController $controller */
?>
<div class="col">
  <label>Název
    <input type="text" placeholder="Název nového štítu" name="<?= CurrentAttackValues::CUSTOM_SHIELD_NAME ?>[0]"
           required="required"></label>
  <label>Potřebná síla <input type="number" min="-20" max="50" value="0"
                              name="<?= CurrentAttackValues::CUSTOM_SHIELD_REQUIRED_STRENGTH ?>[0]"
                              required="required"></label>
  <label>Omezení <input type="number" min="-10" max="20" value="0"
                        name="<?= CurrentAttackValues::CUSTOM_SHIELD_RESTRICTION ?>[0]"
                        required="required"></label>
  <label>Kryt <input type="number" min="-10" max="20" value="1"
                     name="<?= CurrentAttackValues::CUSTOM_SHIELD_COVER ?>[0]"
                     required="required"></label>
  <label>Váha v kg <input type="number" min="0" max="99.99" value="0.5"
                          name="<?= CurrentAttackValues::CUSTOM_SHIELD_WEIGHT ?>[0]"
                          required="required"></label>
  <input type="submit" value="Přidat štít">
</div>
<div class="col-sm-1">
  <a class="button cancel" href="<?= $controller->getCurrentUrlWithQuery([AttackController::ACTION => '']); ?>">Zrušit</a>
</div>
