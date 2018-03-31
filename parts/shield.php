<?php
namespace DrdPlus\Calculators\AttackSkeleton;

/** @var Controller $controller */
?>

<div class="panel">
    <label class="block">
        <select name="<?= Controller::SHIELD ?>"><?php
            /** @var array $shield */
            foreach ($controller->getShields() as $shield) {
                $shieldCode = $shield['code']; ?>
                <option value="<?= $shieldCode->getValue() ?>"
                        <?php if ($controller->getFight()->getSelectedShield()->getValue() === $shieldCode->getValue()) { ?>selected<?php }
                        if (!$shield['canUseIt']) { ?>disabled<?php } ?>>
                    <?= (!$shield['canUseIt'] ? '💪 ' : '') . $shieldCode->translateTo('cs') . ($controller->getFight()->getCoverOfShield($shieldCode) > 0 ? (' +' . $controller->getFight()->getCoverOfShield($shieldCode)) : '') ?>
                </option>
            <?php } ?>
        </select>
    </label>
</div>
