<?php
namespace DrdPlus\AttackSkeleton;

/** @var AttackController $controller */
?>
<div class="col body-properties">
    <div class="col">
        <div class="row"><label for="strength">Síla</label></div>
        <div class="row"><input id="strength" type="number" name="<?= AttackController::STRENGTH ?>" min="-40" max="40"
                                  value="<?= $controller->getCurrentProperties()->getCurrentStrength()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="agility">Obratnost</label></div>
        <div class="row"><input id="agility" type="number" name="<?= AttackController::AGILITY ?>" min="-40" max="40"
                                  value="<?= $controller->getCurrentProperties()->getCurrentAgility()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="knack">Zručnost</label></div>
        <div class="row"><input id="knack" type="number" name="<?= AttackController::KNACK ?>" min="-40" max="40"
                                  value="<?= $controller->getCurrentProperties()->getCurrentKnack()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="will">Vůle</label></div>
        <div class="row"><input id="will" type="number" name="<?= AttackController::WILL ?>" min="-40" max="40"
                                  value="<?= $controller->getCurrentProperties()->getCurrentWill()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="intelligence">Inteligence</label></div>
        <div class="row">
            <input id="intelligence" type="number" name="<?= AttackController::INTELLIGENCE ?>" min="-40" max="40"
                   value="<?= $controller->getCurrentProperties()->getCurrentIntelligence()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="charisma">Charisma</label></div>
        <div class="row">
            <input id="charisma" type="number" name="<?= AttackController::CHARISMA ?>" min="-40" max="40"
                   value="<?= $controller->getCurrentProperties()->getCurrentCharisma()->getValue() ?>"></div>
    </div>
    <div class="col">
        <div class="row"><label for="height">Výška v cm</label></div>
        <div class="row">
            <input id="height" type="number" name="<?= AttackController::HEIGHT_IN_CM ?>" min="110"
                   max="290"
                   value="<?= $controller->getCurrentProperties()->getCurrentHeightInCm()->getValue() ?>">
        </div>
    </div>
    <div class="col">
        <div class="row"><label for="size">Velikost</label></div>
        <div class="row"><input id="size" type="number" name="<?= AttackController::SIZE ?>" min="-10" max="10"
                                  value="<?= $controller->getCurrentProperties()->getCurrentSize()->getValue() ?>">
        </div>
    </div>
</div>
